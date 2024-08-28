<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Controller;

use App\Controller\UsersController;
use App\Dto\CreateUser;
use App\Entity\User;
use App\Service\UserService;
use App\Tests\Unit\Helper;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UsersControllerTest extends TestCase
{
    public function testCreateHandler(): void
    {
        $user = (new User())
            ->setId(1)
            ->setName('John Doe')
            ->setEmail('john@doe.local')
            ->setCreatedAtNow()
        ;

        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock
            ->shouldReceive('create')
            ->with(Mockery::on(function ($argument) {
                return $argument instanceof CreateUser;
            }))
            ->andReturn($user)
        ;

        $controller = new UsersController($userServiceMock);
        $controller->setContainer($this->getContainerMock());

        $request = $this->getRequestMock(['name' => 'John Doe', 'email' => 'john@doe.local']);
        $response = $controller->createHandler($request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(StatusCode::STATUS_CREATED, $response->getStatusCode());
    }

    public function testListHandler(): void
    {
        $users = Helper::getUsers();

        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock
            ->shouldReceive('list')
            ->withArgs([Mockery::type('null'), Mockery::type('null')])
            ->andReturn($users)
        ;

        $controller = new UsersController($userServiceMock);
        $controller->setContainer($this->getContainerMock());

        $request = $this->getRequestMock();
        $response = $controller->listHandler($request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(StatusCode::STATUS_OK, $response->getStatusCode());
    }

    public function testGetHandler(): void
    {
        $user = (new User())
            ->setId(1)
            ->setName('John Doe')
            ->setEmail('john@doe.local')
            ->setCreatedAtNow()
        ;

        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock
            ->shouldReceive('getById')
            ->withArgs([Mockery::type('int')])
            ->andReturn($user)
        ;

        $controller = new UsersController($userServiceMock);
        $controller->setContainer($this->getContainerMock());
        $response = $controller->getHandler(1);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(StatusCode::STATUS_OK, $response->getStatusCode());
    }

    protected function getRequestMock(
        array $bodyData = [],
        array $queryData = [],
        string $contentType = 'application/json'
    ): Request {
        return new Request(
            query: $queryData,
            request: [],
            attributes: [],
            cookies: [],
            files: [],
            server: [
                'CONTENT_TYPE' => $contentType,
            ],
            content: \json_encode($bodyData),
        );
    }

    protected function getContainerMock(): ContainerInterface
    {
        $container = Mockery::mock(ContainerInterface::class);
        $container
            ->shouldReceive('has')
            ->withArgs(['serializer'])
            ->andReturn(false)
        ;
        $container
            ->shouldReceive('has')
            ->withArgs(['twig'])
            ->andReturn(false)
        ;

        return $container;
    }
}
