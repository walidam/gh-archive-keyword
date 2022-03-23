<?php

namespace App\Application\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class RequestListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $event->setResponse(
            new JsonResponse(
                ['message' => $exception->getMessage()],
                $exception->getStatusCode()
            )
        );
    }
}
