<?php

namespace App\Tests\Behat\Mock;

include __DIR__ . '/../../../vendor/autoload.php';

use WireMock\Client\WireMock;
use WireMock\Fault\UniformDistribution;
use App\Tests\Behat\Mock\GhArchive\Archive;

class Mock
{
    public const RESOURCES_DIR = __DIR__ . '/Resources/';

    public static function init()
    {
        try {
            $wireMock = WireMock::create('wiremock', '8080');

            Archive::prepareMock($wireMock);

        } catch (\Throwable $e) {
            echo("catch".$e->getMessage());
            throw $e;
        }
    }
}
Mock::init();
