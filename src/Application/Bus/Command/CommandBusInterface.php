<?php

namespace App\Application\Bus\Command;

use Symfony\Component\Messenger\Envelope;

interface CommandBusInterface
{
    public function handle(CommandInterface $command): ?Envelope;
}
