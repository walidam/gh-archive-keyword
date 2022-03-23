<?php

namespace App\Domain\Repository;

use App\Domain\Dto\Actor;

interface IWriteActorRepository
{
    public function create(Actor $actor): int;
}
