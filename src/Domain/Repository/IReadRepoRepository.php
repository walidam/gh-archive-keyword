<?php

namespace App\Domain\Repository;

use App\Domain\Dto\Repo;

interface IReadRepoRepository
{
    public function getById(int $id): ?Repo;
}
