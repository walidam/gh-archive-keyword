<?php

namespace App\Application\UI\Http\Command\UpdateEvent;

use App\Application\UI\Http\Bus\Command\CommandInterface;

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
