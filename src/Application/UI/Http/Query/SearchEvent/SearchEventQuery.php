<?php

namespace App\Application\UI\Http\Query\SearchEvent;

use App\Application\UI\Http\Bus\Query\QueryInterface;

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
