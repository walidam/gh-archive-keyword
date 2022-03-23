<?php

namespace App\Domain\Repository;

interface IWriteEventRepository
{
    public function create(array $data): void;
    public function update(string $comment, int $id): void;
}
