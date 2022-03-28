<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\IReadEvent;
use App\Domain\Dto\SearchInput;
use Doctrine\DBAL\Connection;

class DbalReadEventRepository implements IReadEvent
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function countAll(SearchInput $searchInput): int
    {
        $sql = <<<SQL
            SELECT sum(count) as count
            FROM event
            WHERE TRUE
        SQL;
        $params = [];
        if (!empty($searchInput->getDate())) {
            $sql .= <<<SQL
                AND date(create_at) = :date
            SQL;
            $params['date'] = $searchInput->getDate()->format("Y-m-d H:i:s");
        }

        if (!empty($searchInput->getKeyword())) {
            $sql .= <<<SQL
                AND payload::text like :keyword
            SQL;
            $params['keyword'] = "%{$searchInput->getKeyword()}%";
        }

        return (int) $this->connection->fetchOne($sql, $params);
    }

    public function countByType(SearchInput $searchInput): array
    {
        $sql = <<<SQL
            SELECT type, sum(count) as count
            FROM event
            WHERE TRUE
        SQL;
        $params = [];
        if (!empty($searchInput->getDate())) {
            $sql .= <<<SQL
                AND date(create_at) = :date
            SQL;
            $params['date'] = $searchInput->getDate()->format("Y-m-d H:i:s");
        }

        if (!empty($searchInput->getKeyword())) {
            $sql .= <<<SQL
                AND payload::text like :keyword
            SQL;
            $params['keyword'] = "%{$searchInput->getKeyword()}%";
        }

        $sql .= <<<SQL
            GROUP BY type
        SQL;

        return $this->connection->fetchAllKeyValue($sql, $params);
    }

    public function statsByTypePerHour(SearchInput $searchInput): array
    {
        $sql = <<<SQL
            SELECT extract(hour from create_at) as hour, type, sum(count) as count
            FROM event
            WHERE TRUE
        SQL;
        $params = [];
        if (!empty($searchInput->getDate())) {
            $sql .= <<<SQL
                AND date(create_at) = :date
            SQL;
            $params['date'] = $searchInput->getDate()->format("Y-m-d H:i:s");
        }

        if (!empty($searchInput->getKeyword())) {
            $sql .= <<<SQL
                AND payload::text like :keyword
            SQL;
            $params['keyword'] = "%{$searchInput->getKeyword()}%";
        }

        $sql .= <<<SQL
            GROUP BY TYPE, EXTRACT(hour from create_at)
        SQL;

        return $this->connection->fetchAllAssociative($sql, $params);
    }

    public function getLatest(SearchInput $searchInput): array
    {
        $sql = <<<SQL
            SELECT type, payload
            FROM event
            WHERE TRUE
        SQL;
        $params = [];
        if (!empty($searchInput->getDate())) {
            $sql .= <<<SQL
                AND date(create_at) = :date
            SQL;
            $params['date'] = $searchInput->getDate()->format("Y-m-d H:i:s");
        }

        if (!empty($searchInput->getKeyword())) {
            $sql .= <<<SQL
                AND payload::text like :keyword
            SQL;
            $params['keyword'] = "%{$searchInput->getKeyword()}%";
        }

        $sql .= <<<SQL
            ORDER BY create_at DESC
            LIMIT 1
        SQL;
        return $this->connection->fetchAllAssociative($sql, $params);
    }

    public function exist(int $id): bool
    {
        $sql = <<<SQL
            SELECT 1
            FROM event
            WHERE id = :id
        SQL;

        $result = $this->connection->fetchOne($sql, [
            'id' => $id
        ]);

        return (bool) $result;
    }
}
