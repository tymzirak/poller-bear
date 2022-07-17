<?php

namespace App\EventSubscriber\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
       return [
           ExceptionEvent::class => ["checkException", 10]
       ];
    }

    public function checkException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof BadRequestHttpException) {
            return null;
        }

        preg_match("/(:\s.+\.)/", $exception->getMessage(), $message);
        if (!$message) {
            preg_match("/(.+\.)/", $exception->getMessage(), $message);
    
            if (!$message) {
                return null;
            }
        }
        
        $message = trim($message[0], ":\n ");

        $event->setResponse(new Response($message));
    }
}
