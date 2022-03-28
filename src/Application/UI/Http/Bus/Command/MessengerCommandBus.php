<?php

namespace App\Application\UI\Http\Bus\Command;

use App\Application\UI\Http\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class MessengerCommandBus implements CommandBusInterface
{
    use MessageBusExceptionTrait;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws Throwable
     */
    public function handle(CommandInterface $command): ?Envelope
    {
        try {
            return $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            $this->throwException($e);
            return null;
        }
    }
}
