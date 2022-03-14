<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof BadRequestHttpException) {
            return null;
        }

        preg_match("/:\s.+\./", $exception->getMessage(), $message);
        if (!$message) {
            return null;
        }
        $message = trim($message[0], ":\n ");

        $event->setResponse(new Response($message));
    }
}
