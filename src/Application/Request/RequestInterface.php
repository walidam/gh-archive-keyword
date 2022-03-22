<?php

namespace App\Application\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestInterface
{
    public function getOriginalRequest(): Request;

    public function setOriginalRequest(Request $originalRequest): RequestInterface;
}
