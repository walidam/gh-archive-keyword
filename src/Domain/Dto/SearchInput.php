<?php

namespace App\Domain\Dto;

class SearchInput
{
    private ?\DateTimeImmutable $date;

    private ?string $keyword;

    public function __construct(?\DateTimeImmutable $date = null, ?string $keyword = null)
    {
        $this->date = $date;
        $this->keyword = $keyword;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    public function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }
}