<?php

namespace App\Event;

use App\Exception\ApiExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        if (!$event->getException() instanceof ApiExceptionInterface) {
            $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
            $data = $this->buildResponseData(Response::$statusTexts[$statusCode],$statusCode);
            $response = new JsonResponse($data, $statusCode);
        } else {
            $data = $this->buildResponseData($e->getMessage(),$e->getStatusCode());
            $response = new JsonResponse($data, $e->getStatusCode());
        }
        $response->headers->set('Content-Type', 'application/problem+json');
        $event->setResponse($response);
    }
    public function buildResponseData($message,$code)
    {
        return [
            'code' => $code,
            'messages' => $message
        ];
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }
}