<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use App\Exception\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof ApiExceptionInterface) {
            return;
        }

        $response = new JsonResponse($event->getException()->getMessage(), $event->getException()->getStatusCode());
        $response->headers->set('Content-Type', 'application/problem+json');
        $event->setResponse($response);
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }
}