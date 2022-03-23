<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\IWriteEvent;
use App\Domain\Dto\EventInput;
use Doctrine\DBAL\Connection;

class DbalWriteEventRepository implements IWriteEvent
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(array $data): void
    {
        $sql = <<<SQL
        INSERT INTO event (id, actor_id, repo_id, type, "count", payload, create_at, comment)
        VALUES (:id, :actor, :repo, :typeEvent, :total, :payload, :createAt, :comment)
SQL;

        $this->connection->executeQuery(
            $sql,
            [
                'id' => $data['id'],
                'actor' => $data['actor'],
                'repo' => $data['repo'],
                'typeEvent' => $data['type'],
                'total' => $data['total'],
                'payload' => $data['payload'],
                'createAt' => $data['created_at'],
                'comment' => $data['comment']
            ]
        );
    }

    public function update(EventInput $authorInput, int $id): void
    {
        $sql = <<<SQL
        UPDATE event
        SET comment = :comment
        WHERE id = :id
SQL;

        $this->connection->executeQuery($sql, ['id' => $id, 'comment' => $authorInput->getComment()]);
    }
}
