<?php

namespace App\Application\Command\UpdateEvent;

use App\Application\Bus\Command\CommandInterface;

class UpdateEventCommand implements CommandInterface
{
    public int $eventId;
    public string $comment;

    public function __construct(int $eventId, string $comment)
    {
        $this->eventId = $eventId;
        $this->comment = $comment;
    }
}
