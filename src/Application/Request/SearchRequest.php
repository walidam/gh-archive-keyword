<?php

namespace App\Application\Request;

class SearchRequest extends AbstractRequest
{
    private ?\DateTimeImmutable $date = null;

    private ?string $keyword = null;

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword($keyword): void
    {
        $this->keyword = $keyword;
    }
}

