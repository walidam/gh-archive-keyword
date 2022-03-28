<?php
namespace App\Tests\Behat;

use App\Domain\Repository\IReadEventRepository;
use App\Domain\Repository\IReadGhEvents;
use App\Domain\Repository\ReadEventRepository;
use App\Infrastructure\Repository\ReadGhEventRepository;
use App\Kernel;
use App\Tests\Behat\Mock\Mock;
use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Webmozart\Assert\Assert;

final class  SystemContext implements Context
{
    private const RESOURCES_DIR = __DIR__ . '/resources/';

    /**
     * @var Application
     */
    private $application;

    /**
     * @var BufferedOutput
     */
    private $output;

    /**
     * @var IReadEventRepository
     */
    private $readEventRepository;

    public function __construct(Kernel $kernel, IReadEventRepository $readEventRepository)
    {
        $this->application = new Application($kernel);
        $this->output = new BufferedOutput();
        $this->readEventRepository = $readEventRepository;
    }

    /**
     * @Given I am the system
     */
    public function iAmTheSystem()
    {
        Assert::same('cli', php_sapi_name());
    }

    /**
     * @When I fetch data from gharchive for :data
     */
    public function iFetchDataFromGhArchive()
    {
        return gzopen(self::RESOURCES_DIR . 'archive.json', 'rb');
    }

    /**
     * @When I execute import for :date hour :hour
     */
    public function iExecuteImport(string $date, int $hour)
    {
        $command = 'app:import-github-events';
        $input = new ArgvInput(['behat-test', $command, '--from='.$date.'-'.$hour, '--to='.$date.'-'.$hour, '--env=test']);
        $this->application->doRun($input, $this->output);
    }

    /**
     * @When I found :date in search
     */
    public function iFoundDateInSearch(string $date)
    {
        $dateTime = new \DateTimeImmutable($date);
        $result = $this->readEventRepository->search($dateTime);

        Assert::eq($result['meta']['totalEvents'], 3, sprintf("No archive found for %s", $date));
    }
}
