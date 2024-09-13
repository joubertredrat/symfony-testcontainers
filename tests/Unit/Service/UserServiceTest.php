<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Service;

use App\Dto\CreateUser;
use App\Entity\User;
use App\Exception\Service\UserService\UserExistsException;
use App\Exception\Service\UserService\UserNotFoundException;
use App\Repository\UserRepositoryInterface;
use App\Service\UserService;
use App\Tests\Unit\Helper;
use DateTimeImmutable;
use Mockery;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testCreateWithSuccess(): void
    {
        $idExpected = 10;
        $createUser = new CreateUser('John Doe', 'john@doe.local');

        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('getByEmail')
            ->withArgs([$createUser->email])
            ->andReturn(null)
        ;
        $userRepository
            ->shouldReceive('save')
            ->with(
                Mockery::on(function(User $entity) use ($idExpected) {
                    $entity->setId($idExpected);
                    $entity->setCreatedAtNow();
                    return true;
                }),
                Mockery::type('bool'),
            )
            ->once()
            ->andReturn()
        ;

        /** @var UserRepositoryInterface $userRepository */
        $userService = new UserService($userRepository);
        $userGot = $userService->create($createUser);

        self::assertEquals($idExpected, $userGot->getId());
        self::assertEquals($createUser->name, $userGot->getName());
        self::assertEquals($createUser->email, $userGot->getEmail());
        self::assertInstanceOf(DateTimeImmutable::class, $userGot->getCreatedAt());
    }

    public function testCreateWithUserExistsException(): void
    {
        $this->expectException(UserExistsException::class);

        $createUser = new CreateUser('John Doe', 'john@doe.local');

        $userFound = new User();
        $userFound->setId(10);
        $userFound->setName('John Doe');
        $userFound->setEmail('john@doe.local');
        $userFound->setCreatedAt(new DateTimeImmutable('2024-08-26 21:06:28'));

        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('getByEmail')
            ->withArgs([$createUser->email])
            ->andReturn($userFound)
        ;

        /** @var UserRepositoryInterface $userRepository */
        $userService = new UserService($userRepository);
        $userService->create($createUser);
    }

    public function testGetByIdWithSuccess(): void
    {
        $id = 10;
        $userExpected = new User();
        $userExpected->setId($id);
        $userExpected->setName('John Doe');
        $userExpected->setEmail('john@doe.local');
        $userExpected->setCreatedAt(new DateTimeImmutable('2024-08-26 21:06:28'));

        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('getById')
            ->withArgs([$id])
            ->andReturn($userExpected)
        ;

        /** @var UserRepositoryInterface $userRepository */
        $userService = new UserService($userRepository);
        $userGot = $userService->getById($id);

        self::assertEquals($userExpected, $userGot);
    }

    public function testGetByIdWithUserNotFoundException(): void
    {
        $this->expectException(UserNotFoundException::class);

        $id = 10;

        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('getById')
            ->withArgs([$id])
            ->andReturn(null)
        ;

        /** @var UserRepositoryInterface $userRepository */
        $userService = new UserService($userRepository);
        $userService->getById($id);
    }

    public function testListWithSuccess(): void
    {
        $usersExpected = Helper::getUsers();

        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('list')
            ->withArgs([Mockery::type('null'), Mockery::type('null')])
            ->andReturn($usersExpected)
        ;

        /** @var UserRepositoryInterface $userRepository */
        $userService = new UserService($userRepository);
        $usersGot = $userService->list();

        self::assertEquals($usersExpected, $usersGot);
    }
}
