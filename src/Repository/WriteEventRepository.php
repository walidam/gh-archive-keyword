<?php

namespace App\Repository;

use App\Dto\EventInput;

interface WriteEventRepository
{
    public function update(EventInput $authorInput, int $id): void;
}
