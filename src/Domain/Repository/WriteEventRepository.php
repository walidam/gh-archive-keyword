<?php

namespace App\Domain\Repository;

use App\Domain\Dto\EventInput;

class WriteEventRepository implements IWriteEventRepository
{
    private IWriteEvent $writeEvent;

    public function __construct(IWriteEvent $writeEvent)
    {
        $this->writeEvent = $writeEvent;
    }

    public function create(array $data): void
    {
        $this->writeEvent->create($data);
    }

    public function update(string $comment, int $id): void
    {
        $authorInput = new EventInput($comment);
        $this->writeEvent->update($authorInput, $id);
    }
}
