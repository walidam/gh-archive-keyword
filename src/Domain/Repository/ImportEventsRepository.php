<?php

namespace App\Domain\Repository;

use App\Domain\Dto\Actor as ActorDto;
use App\Domain\Dto\Repo as RepoDto;
use App\Infrastructure\Entity\EventType;

class ImportEventsRepository implements IImportEventsRepository
{
    protected static array $events = [
        'PushEvent' => EventType::COMMIT,
        'PullRequestEvent' => EventType::PULL_REQUEST,
        'IssueCommentEvent' => EventType::COMMENT,
        'CommitCommentEvent' => EventType::COMMENT,
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
        foreach ($this->readGhEvent->get($date) as $data) {
            if (!array_key_exists($data['type'], self::$events)) {
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
                if ($type === EventType::COMMENT) {
                    $comment = $data['payload']['comment']['url'];
                } else {
                    $comment = '';
                }
                $createdAt = new \DateTime($data['created_at']);
                $this->dbalWriteEventRepository->create([
                    'id' => $data['id'],
                    'actor' => $actorId,
                    'repo' => $repoId,
                    'type' => $type,
                    'total' => 1,
                    'payload' => json_encode($data['payload']),
                    'created_at' => $createdAt->format('Y-m-d h:i:s'),
                    'comment' => $comment
                ]);
            }
        }
    }
}
