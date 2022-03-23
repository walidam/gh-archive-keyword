<?php

namespace App\Domain\Repository;

use App\Domain\Dto\Actor;

interface IReadActorRepository
{
    public function getById(int $id): ?Actor;
}
