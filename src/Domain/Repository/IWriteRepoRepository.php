<?php

namespace App\Domain\Repository;

use App\Domain\Dto\Repo;

interface IWriteRepoRepository
{
    public function create(Repo $repo): int;
}
