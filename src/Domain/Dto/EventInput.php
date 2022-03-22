<?php

namespace App\Domain\Dto;

class EventInput
{
    private string $comment;

    public function __construct(string $comment)
    {
        $this->comment = $comment;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}
