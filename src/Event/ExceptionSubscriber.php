<?php

namespace App\Event;

use App\Exception\ApiExceptionInterface;
use App\Factory\ErrorResponseFactory;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        $factory = new ErrorResponseFactory();
        if ($e instanceof AuthenticationException) {
            $response = $factory->create($e->getMessageKey(),401);
        } else if (!$e instanceof ApiExceptionInterface) {
            $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
            $response = $factory->create(Response::$statusTexts[$statusCode],$statusCode);
        } else {
            $response = $factory->create($e->getMessage(),$e->getCode());
        }
        $event->setResponse($response);
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }
}