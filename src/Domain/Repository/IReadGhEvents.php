<?php

namespace App\Domain\Repository;

interface IReadGhEvents
{
    public function get(\DateTimeInterface $date): iterable;
}
