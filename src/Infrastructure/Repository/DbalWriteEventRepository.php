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
