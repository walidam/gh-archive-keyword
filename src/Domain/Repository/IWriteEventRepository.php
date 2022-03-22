<?php

namespace App\Domain\Repository;

use App\Domain\Dto\EventInput;

interface IWriteEventRepository
{
    public function update(string $comment, int $id): void;
}
