<?php

namespace App\Application\UI\Http\Request;

class SearchRequest extends AbstractRequest
{
    private ?\DateTimeInterface $date = null;

    private ?string $keyword = null;

    public function getDate(): ?\DateTimeInterface
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
