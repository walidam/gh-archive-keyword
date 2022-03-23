<?php

namespace App\Domain\Dto;

class SearchInput
{
    private ?\DateTimeInterface $date;

    private ?string $keyword;

    public function __construct(?\DateTimeInterface $date = null, ?string $keyword = null)
    {
        $this->date = $date;
        $this->keyword = $keyword;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
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
