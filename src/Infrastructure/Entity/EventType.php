<?php

namespace App\Infrastructure\Entity;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class EventType extends AbstractEnumType
{
    public const COMMIT = 'COM';
    public const COMMENT = 'MSG';
    public const PULL_REQUEST = 'PR';

    protected static array $choices = [
        self::COMMIT => 'commit',
        self::COMMENT => 'comment',
        self::PULL_REQUEST => 'pullRequest',
    ];

    public static function getChoice($type)
    {
        return self::$choices[$type];
    }
}
