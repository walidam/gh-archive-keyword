<?php

namespace App\Domain\Repository;

interface IReadEventRepository
{
    public function search(?\DateTimeInterface $date = null, ?string $keyword = null): array;
    public function exist(int $id): bool;
}
