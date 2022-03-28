<?php

namespace App\Domain\Repository;

use App\Domain\Dto\Actor as ActorDto;
use App\Domain\Dto\Repo as RepoDto;
use App\Infrastructure\Entity\EventType;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;

class ImportEventsRepository implements IImportEventsRepository
{
    protected static array $events = [
        'PushEvent'                     => EventType::COMMIT,
        'PullRequestEvent'              => EventType::PULL_REQUEST,
        'IssueCommentEvent'             => EventType::COMMENT,
        'CommitCommentEvent'            => EventType::COMMENT,
        'PullRequestReviewCommentEvent' => EventType::COMMENT,
    ];

    private IReadGhEvents $readGhEvent;
    private IWriteActorRepository $ormWriteActorRepository;
    private IWriteRepoRepository $ormWriteRepoRepository;
    private IWriteEventRepository $dbalWriteEventRepository;
    private IReadEventRepository $dbalReadEventRepository;
    private IReadActorRepository $ormReadActorRepository;
    private IReadRepoRepository $ormReadRepoRepository;

    public function __construct(
        IReadGhEvents $readGhEvent,
        IWriteActorRepository $ormWriteActorRepository,
        IWriteRepoRepository $ormWriteRepoRepository,
        IWriteEventRepository $dbalWriteEventRepository,
        IReadEventRepository $dbalReadEventRepository,
        IReadActorRepository $ormReadActorRepository,
        IReadRepoRepository $ormReadRepoRepository
    ) {
        $this->readGhEvent = $readGhEvent;
        $this->ormWriteActorRepository = $ormWriteActorRepository;
        $this->ormWriteRepoRepository = $ormWriteRepoRepository;
        $this->dbalWriteEventRepository = $dbalWriteEventRepository;
        $this->dbalReadEventRepository = $dbalReadEventRepository;
        $this->ormReadActorRepository = $ormReadActorRepository;
        $this->ormReadRepoRepository = $ormReadRepoRepository;
    }

    public function import(\DateTimeInterface $date): void
    {
        foreach ($this->getArchive($date) as $data) {
            if (!array_key_exists($data['type'], self::$events)) {
                unset($data);
                continue;
            }
            $actorData = $data['actor'];
            if (!$actor = $this->ormReadActorRepository->getById($actorData['id'])) {
                $actorId = $this->ormWriteActorRepository->create(ActorDto::fromArray($actorData));
            } else {
                $actorId = $actor->getId();
            }

            $repoData = $data['repo'];
            if (!$repo = $this->ormReadRepoRepository->getById($repoData['id'])) {
                $repoId = $this->ormWriteRepoRepository->create(RepoDto::fromArray($repoData));
            } else {
                $repoId = $repo->getId();
            }

            if (!$this->dbalReadEventRepository->exist($data['id'])) {
                $type = self::$events[$data['type']];
                $comment = '';
                $count = 1;
                if ($type === EventType::COMMENT) {
                    $comment = $data['payload']['comment']['url'];
                }
                if ($type === EventType::COMMIT) {
                    $count = count($data['payload']['commits']);
                }
                $createdAt = new \DateTime($data['created_at']);
                $this->dbalWriteEventRepository->create([
                    'id' => $data['id'],
                    'actor' => $actorId,
                    'repo' => $repoId,
                    'type' => $type,
                    'total' => $count,
                    'payload' => json_encode($data['payload']),
                    'created_at' => $createdAt->format('Y-m-d H:i:s'),
                    'comment' => $comment
                ]);
            }
            unset($data);
        }
    }

    private function getArchive($date)
    {
        $day = $date->format('Y-m-d');
        $hour = $date->format('G');
        $filename = "gh-{$day}-{$hour}";
        do {
            $name = $filename.mt_rand();
            $fileName = sys_get_temp_dir() ."/{$name}";
            $fileGz = "{$fileName}.gz";
            $gzTemp = fopen($fileGz, 'x+');
        } while (!$gzTemp);

        $stream = $this->readGhEvent->getArchive($day, $hour);
        while ($content = $stream->read(1024)) {
            fwrite($gzTemp, $content);
        }

        $this->gunzip($fileGz);

        $events = Items::fromFile($fileName, ['decoder' => new ExtJsonDecoder(true)]);
        foreach ($events as $key => $value) {
            yield $value;
            gc_collect_cycles();
        }
        unlink($fileName);
        unlink($fileGz);
    }

    private function gunzip($filename)
    {
        $bufferSize = 4096; // read 4kb at a time
        $outFileName = str_replace('.gz', '', $filename);

        $file = gzopen($filename, 'rb');
        $outFile = fopen($outFileName, 'wb');
        fwrite($outFile, '[');
        while (!gzeof($file)) {
            fwrite($outFile, preg_replace('/}\s{"id"/', '},{"id"', gzread($file, $bufferSize)));
        }
        fwrite($outFile, ']');

        fclose($outFile);
        gzclose($file);
    }
}
