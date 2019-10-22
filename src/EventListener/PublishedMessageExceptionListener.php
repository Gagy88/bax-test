<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class PublishedMessageExceptionListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception
         = $event->getException();

        $code = $exception->getStatusCode();
        $responseData = [
            'error' => [
                'code' => $code,
                'message' => $exception->getMessage(),
            ],
        ];

        $event->setResponse(new JsonResponse($responseData, $code));
    }
}
