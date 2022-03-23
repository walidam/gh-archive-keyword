<?php

namespace App\Tests\Unit\Domain\Repository;

use App\Domain\Dto\SearchInput;
use App\Domain\Repository\IReadEvent;
use App\Domain\Repository\ReadEventRepository;
use App\Infrastructure\DataFixtures\EventFixtures;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Mockery;
use PHPUnit\Framework\TestCase;

class ReadEventRepositoryTest extends TestCase
{
    private IReadEvent $readEvent;
    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        $this->readEvent = Mockery::mock(IReadEvent::class);
    }

    /**
     * @dataProvider providePayload
     */
    public function testSearchEvent(bool $isViolation, ?string $date, ?string $keyword, string $expectedResponse)
    {
        $repository = new ReadEventRepository($this->readEvent);
        $searchInput = Mockery::mock(SearchInput::class);
        $this->readEvent
            ->shouldReceive('countByType')
            ->andReturn($isViolation ? [] : ["MSG" => 1]);
        $this->readEvent
            ->shouldReceive('getLatest')
            ->andReturn($isViolation ? [] : [["type" => "MSG", "repo" => 1]]);
        $this->readEvent
            ->shouldReceive('statsByTypePerHour')
            ->andreturn($isViolation ? [] : [["hour" => "0", "type" => "MSG", "count" => 1]]);

        $this->readEvent->shouldReceive('countAll')->andreturn($isViolation ? 0 : 1);
        $result = $repository->search(new \DateTimeImmutable($date), $keyword);
        $this->assertJsonStringEqualsJsonString(json_encode($result),json_encode(json_decode($expectedResponse)));
    }

    public function providePayload(): iterable
    {
        yield 'Test with existing date' => [
            false,
            '2022-03-21',
            null,
            <<<JSON
              {
                "meta": {
                    "totalEvents": 1,
                    "totalPullRequests": 0,
                    "totalCommits": 0,
                    "totalComments": 1
                },
                "data": {
                    "events": [
                        {
                            "type": "MSG",
                            "repo": 1
                        }
                    ],
                    "stats": [
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 1
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        }
                    ]
                }
              }
            JSON,
        ];

        yield 'Test with existing keyword' => [
            false,
            null,
            'yousign',
            <<<JSON
              {
                "meta": {
                    "totalEvents": 1,
                    "totalPullRequests": 0,
                    "totalCommits": 0,
                    "totalComments": 1
                },
                "data": {
                    "events": [
                        {
                            "type": "MSG",
                            "repo": 1
                        }
                    ],
                    "stats": [
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 1
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        }
                    ]
                }
              }
            JSON,
        ];
        yield 'Test with not existing keyword' => [
            true,
            null,
            'not-exist',
            <<<JSON
              {
                "meta": {
                    "totalEvents": 0,
                    "totalPullRequests": 0,
                    "totalCommits": 0,
                    "totalComments": 0
                },
                "data": {
                    "events": [],
                    "stats": [
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        },
                        {
                            "commit": 0,
                            "pullRequest": 0,
                            "comment": 0
                        }
                    ]
                }
              }
            JSON,
        ];
    }
}

