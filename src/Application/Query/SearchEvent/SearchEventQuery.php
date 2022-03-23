<?php

namespace App\Application\Query\SearchEvent;

use App\Application\Bus\Query\QueryInterface;

class SearchEventQuery implements QueryInterface
{
    public ?\DateTimeInterface $date;

    public ?string $keyword;

    public function __construct(?\DateTimeInterface $date = null, ?string $keyword = null)
    {
        $this->date = $date;
        $this->keyword = $keyword;
    }
}
