<?php

namespace App\Domain\Repository;

interface IReadEventRepository
{
    public function search(?\DateTimeImmutable $date = null, ?string $keyword = null): array;
    public function exist(int $id): bool;
}
