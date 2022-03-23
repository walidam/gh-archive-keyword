<?php

namespace App\Application\Command\ImportEvents;

use App\Domain\Repository\IImportEventsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command must import GitHub events.
 * You can add the parameters and code you want in this command to meet the need.
 */
class ImportGitHubEventsCommand extends Command
{
    const BEGIN_DATE = '2011/2/12';
    protected static $defaultName = 'app:import-github-events';
    private IImportEventsRepository $repository;

    public function __construct(IImportEventsRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import GH events')
            ->addOption(
                'date',
                'dt',
                InputOption::VALUE_OPTIONAL,
                'Day of begin import (format Y/m/d)',
                self::BEGIN_DATE
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = new \DateTime($input->getOption('date'));
        $date->modify('-1 day');
        $now = new \DateTime();

        while ($date->modify('+1 day') <= $now) {
            $output->writeln('Import archive for ' . $date->format('Y-m-d'));
            $this->repository->import($date);
        }

        return 1;
    }
}
