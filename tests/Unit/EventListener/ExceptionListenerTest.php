<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventListener;

use App\EventListener\ExceptionListener;
use App\Exception\Service\UserService\UserExistsException;
use App\Exception\Service\UserService\UserNotFoundException;
use Exception;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Throwable;

class ExceptionListenerTest extends TestCase
{
    public function testOnKernelExceptionWithUserExistsException(): void
    {
        $dispatcher = $this->getEventDispatcherMock();
        $event = $this->getExceptionEvent(UserExistsException::create('john@doe.local'));
        $dispatcher->dispatch($event, 'onKernelException');

        $response = $event->getResponse();
        self::assertInstanceOf(JsonResponse::class, $event->getResponse());
        self::assertEquals(StatusCode::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function testOnKernelExceptionWithUserNotFoundException(): void
    {
        $dispatcher = $this->getEventDispatcherMock();
        $event = $this->getExceptionEvent(UserNotFoundException::create(10));
        $dispatcher->dispatch($event, 'onKernelException');

        $response = $event->getResponse();
        self::assertInstanceOf(JsonResponse::class, $event->getResponse());
        self::assertEquals(StatusCode::STATUS_NOT_FOUND, $response->getStatusCode());
    }

    public function testOnKernelExceptionWithUnknownException(): void
    {
        $dispatcher = $this->getEventDispatcherMock();
        $event = $this->getExceptionEvent(new Exception('Uai?'));
        $dispatcher->dispatch($event, 'onKernelException');

        $response = $event->getResponse();
        self::assertInstanceOf(JsonResponse::class, $event->getResponse());
        self::assertEquals(StatusCode::STATUS_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    protected function getExceptionEvent(Throwable $e): ExceptionEvent
    {
        return new ExceptionEvent(
            kernel: Mockery::mock(HttpKernelInterface::class),
            request: Request::createFromGlobals(),
            requestType: HttpKernelInterface::MAIN_REQUEST,
            e: $e,
        );
    }

    protected function getEventDispatcherMock(): EventDispatcher
    {
        $dispatcher = new EventDispatcher();
        $listener = new ExceptionListener();
        $dispatcher->addListener('onKernelException', [$listener, 'onKernelException']);
        return $dispatcher;
    }
}
