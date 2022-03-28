<?php

namespace App\Tests\Behat\Mock\GhArchive;

use App\Tests\Behat\Mock\Mock;
use WireMock\Client\WireMock;

class Archive
{
    private const BASE_PATH = '/data.gharchive.org';
    private const RESPONSE_CONTENT_TYPE = 'application/gzip';

    public static function prepareMock(WireMock $wireMock)
    {
       $wireMock->stubFor(
            WireMock::GET(
                WireMock::urlMatching(self::BASE_PATH . '/([0-9]{4}-[0-9]{2}-[0-9]{2}-[0-9]{1,2}).json.gz')
            )
            ->atPriority(2)
            ->willReturn(
                WireMock::aResponse()
                    ->withTransformers('response-template')
                    ->withStatus(200)
                    ->withHeader('Content-Type', self::RESPONSE_CONTENT_TYPE)
                    ->withBodyData(file_get_contents(Mock::RESOURCES_DIR . 'archive.json'))
            )
        );
    }
}
