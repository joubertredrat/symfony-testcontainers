<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\Service\UserService\UserExistsException;
use App\Exception\Service\UserService\UserNotFoundException;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof UserExistsException) {
            $event->setResponse(new JsonResponse(
                data: ['error' => $exception->getMessage()],
                status: StatusCode::STATUS_UNPROCESSABLE_ENTITY,
            ));
            return;
        }
        if ($exception instanceof UserNotFoundException) {
            $event->setResponse(new JsonResponse(
                data: ['error' => $exception->getMessage()],
                status: StatusCode::STATUS_NOT_FOUND,
            ));
            return;
        }
        if ($exception instanceof Throwable) {
            $event->setResponse(new JsonResponse(
                data: ['error' => 'I have no idea that\'s happening here'],
                status: StatusCode::STATUS_INTERNAL_SERVER_ERROR,
            ));
        }
    }
}
