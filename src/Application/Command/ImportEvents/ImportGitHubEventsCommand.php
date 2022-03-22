<?php

namespace App\Application\Command\ImportEvents;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command must import GitHub events.
 * You can add the parameters and code you want in this command to meet the need.
 */
class ImportGitHubEventsCommand extends Command
{
    protected static $defaultName = 'app:import-github-events';

    protected function configure(): void
    {
        $this
            ->setDescription('Import GH events');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Let's rock !
        // It's up to you now

        return 1;
    }
}
