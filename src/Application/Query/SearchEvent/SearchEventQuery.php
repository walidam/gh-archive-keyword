<?php

namespace App\Application\Query\SearchEvent;

use App\Application\Bus\Query\QueryInterface;

class SearchEventQuery implements QueryInterface
{
    public ?\DateTimeImmutable $date;

    public ?string $keyword;

    public function __construct(?\DateTimeImmutable $date = null, ?string $keyword = null)
    {
        $this->date = $date;
        $this->keyword = $keyword;
    }
}
