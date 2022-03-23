<?php

namespace App\Domain\Repository;

use App\Domain\Dto\SearchInput;
use App\Infrastructure\Entity\EventType;

class ReadEventRepository implements IReadEventRepository
{
    private IReadEvent $readEvent;

    public function __construct(IReadEvent $readEvent)
    {
        $this->readEvent = $readEvent;
    }

    public function search(?\DateTimeInterface $date = null, ?string $keyword = null): array
    {
        $searchInput = new SearchInput($date, $keyword);
        $countByType = $this->readEvent->countByType($searchInput);

        $latest = $this->readEvent->getLatest($searchInput);
        $latest = array_map(static function ($item) {
            $item['repo'] = json_decode($item['repo'], true);

            return $item;
        }, $latest);

        $stats = $this->readEvent->statsByTypePerHour($searchInput);
        $dataStats = array_fill(
            0,
            24,
            [
                EventType::getChoice(EventType::COMMIT) => 0,
                EventType::getChoice(EventType::PULL_REQUEST) => 0,
                EventType::getChoice(EventType::COMMENT) => 0
            ]
        );

        foreach ($stats as $stat) {
            $dataStats[(int) $stat['hour']][EventType::getChoice($stat['type'])] = $stat['count'];
        }

        return [
            'meta' => [
                'totalEvents' => $this->readEvent->countAll($searchInput),
                'totalPullRequests' => $countByType[EventType::PULL_REQUEST] ?? 0,
                'totalCommits' => $countByType[EventType::COMMIT] ?? 0,
                'totalComments' => $countByType[EventType::COMMENT] ?? 0,
            ],
            'data' => [
                'events' => $latest,
                'stats' => $dataStats
            ]
        ];
    }

    public function exist(int $id): bool
    {
        return $this->readEvent->exist($id);
    }
}
