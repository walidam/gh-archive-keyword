<?php

namespace App\Domain\Repository;

interface IImportEventsRepository
{
    public function import(\DateTimeInterface $date): void;
}
