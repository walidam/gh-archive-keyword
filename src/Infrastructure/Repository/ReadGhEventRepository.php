<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\IReadGhEvents;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;

class ReadGhEventRepository implements IReadGhEvents
{
    public function get(\DateTimeInterface $date): iterable
    {
        $day = $date->format('Y-m-d');
        for ($hour = 0; $hour < 1; $hour++) {
            $file = tempnam("/tmp", "gh-{$day}-{$hour}");
            $temp = fopen($file, "r+");
            $gz = gzopen("https://data.gharchive.org/{$day}-{$hour}.json.gz", 'rb');
            fwrite($temp, '[');
            while (!gzeof($gz)) {
                fwrite($temp, gzread($gz, 500));
            }
            $fileSize = filesize($file);
            if ($fileSize === 0) {
                continue;
            }
            fwrite($temp, ']');
            gzclose($gz);
            rewind($temp);
            $events = Items::fromFile($file, ['decoder' => new ExtJsonDecoder(true), 'debug' => true]);
            foreach ($events as $key => $value) {
                yield $value;
            }
            fclose($temp);
            unlink($file);
        }
    }
}
