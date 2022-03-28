<?php
namespace App\Infrastructure\Repository;

use App\Domain\Repository\IReadGhEvents;
use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class ReadGhEventRepository implements IReadGhEvents
{
    private string $ghArchiveUrl;

    public function __construct(string $ghArchiveUrl)
    {
        $this->ghArchiveUrl = $ghArchiveUrl;
    }
    public function getArchive(string $day, string $hour): StreamInterface
    {
        $client = new Client();

        $response = $client->request('GET', "{$this->ghArchiveUrl}/{$day}-{$hour}.json.gz", [
            'stream' => true
        ]);

        return $response->getBody();
    }
}
