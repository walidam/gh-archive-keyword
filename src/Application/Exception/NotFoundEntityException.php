<?php

namespace App\Application\Exception;

use Exception;

class NotFoundEntityException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
