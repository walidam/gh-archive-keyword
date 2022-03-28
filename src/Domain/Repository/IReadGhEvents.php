<?php

namespace App\Domain\Repository;

use Psr\Http\Message\StreamInterface;

interface IReadGhEvents
{
    public function getArchive(string $day, string $hour): StreamInterface;
}
