<?php

namespace App\Application\Command\UpdateEvent;

use App\Application\Bus\Command\CommandHandlerInterface;
use App\Application\Exception\NotFoundEntityException;
use App\Application\Exception\ServiceUnavailableException;
use App\Domain\Repository\IReadEventRepository;
use App\Domain\Repository\IWriteEventRepository;

class UpdateEventHandler implements CommandHandlerInterface
{
    private IReadEventRepository $readEventRepository;
    private IWriteEventRepository $writeEventRepository;

    public function __construct(IReadEventRepository $readEventRepository, IWriteEventRepository $writeEventRepository)
    {
        $this->readEventRepository = $readEventRepository;
        $this->writeEventRepository = $writeEventRepository;
    }

    /**
     * @throws NotFoundEntityException
     * @throws ServiceUnavailableException
     */
    public function __invoke(UpdateEventCommand $command): void
    {
        if ($this->readEventRepository->exist($command->eventId) === false) {
            throw new NotFoundEntityException(sprintf('Event identified by %d not found !', $command->eventId));
        }

        try {
            $this->writeEventRepository->update($command->comment, $command->eventId);
        } catch (\Exception $exception) {
            throw new ServiceUnavailableException();
        }
    }
}
