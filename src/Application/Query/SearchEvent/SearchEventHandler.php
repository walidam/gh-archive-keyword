<?php

namespace App\Application\Query\SearchEvent;

use App\Application\Bus\Query\QueryHandlerInterface;
use App\Domain\Dto\SearchInput;
use App\Domain\Repository\IReadEventRepository;

class SearchEventHandler implements QueryHandlerInterface
{
    private IReadEventRepository $repository;

    public function __construct(IReadEventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SearchEventQuery $query): array
    {
        return $this->repository->search($query->date, $query->keyword);
    }
}
