<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionToJsonListener
{
    private bool $isDebug;

    public function __construct(KernelInterface $kernel)
    {
        $this->isDebug = $kernel->isDebug();
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($this->isDebug) {
            return;
        }

        // You get the exception object from the received event
        $exception = $event->getThrowable();
        // Customize your response object to display the exception details
        $response = new JsonResponse();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setData(['code' => $response->getStatusCode(), 'message' => $exception->getMessage()]);

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
