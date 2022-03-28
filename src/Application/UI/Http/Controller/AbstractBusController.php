<?php

namespace App\Application\UI\Http\Controller;

use App\Application\UI\Http\Bus\Command\CommandBusInterface;
use App\Application\UI\Http\Bus\Command\CommandInterface;
use App\Application\UI\Http\Bus\Query\QueryBusInterface;
use App\Application\UI\Http\Bus\Query\QueryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractBusController extends AbstractController
{
    protected CommandBusInterface $commandBus;

    protected QueryBusInterface $queryBus;

    protected function handle(CommandInterface $command)
    {
        return $this->commandBus->handle($command);
    }

    protected function getLastHandled(?Envelope $envelope)
    {
        if (!$envelope) {
            return null;
        }

        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp ? $handledStamp->getResult() : null;
    }

    protected function ask(QueryInterface $query)
    {
        return $this->queryBus->ask($query);
    }

    /**
     * @required
     */
    public function setCommandBus(CommandBusInterface $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @required
     */
    public function setQueryBus(QueryBusInterface $queryBus): void
    {
        $this->queryBus = $queryBus;
    }

    protected function getCommandBus()
    {
        return $this->commandBus;
    }

    protected function getQueryBus()
    {
        return $this->queryBus;
    }
}
