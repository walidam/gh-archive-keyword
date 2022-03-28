<?php

namespace App\Application\UI\Cli\Command;

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
                'from',
                null,
                InputOption::VALUE_OPTIONAL,
                'Day of begin import (format Y/m/d-G. Example 2022-03-20-0)',
                self::BEGIN_DATE
            )->addOption(
                'to',
                null,
                InputOption::VALUE_OPTIONAL,
                'Day of end import (format Y/m/d-G. Example 2022-03-20-0)',
                'now'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$from = \DateTime::createFromFormat('Y-m-d-G', $input->getOption('from'))) {
            $output->writeln('From date is not valid. Try "Y/m/d-G" format.');
            return 0;
        }
        if (!$to = \DateTime::createFromFormat('Y-m-d-G', $input->getOption('to'))) {
            $output->writeln('To date is not valid. Try "Y/m/d-G" format');
            return 0;
        }
        $from->modify('-1 hour');

        while ($from->modify('+1 hour') <= $to) {
            $output->writeln(
                'Import archive from ' . $from->format('Y-m-d H') . ' to '. $to->format('Y-m-d H')
            );
            $this->repository->import($from);
        }

        return 1;
    }
}
