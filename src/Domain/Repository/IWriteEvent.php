<?php

namespace App\Domain\Repository;

use App\Domain\Dto\EventInput;

interface IWriteEvent
{
    public function create(array $data): void;
    public function update(EventInput $authorInput, int $id): void;
}
