<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Service;

use App\Dto\CreateUser;
use App\Entity\User;
use App\Exception\Service\UserService\UserExistsException;
use App\Exception\Service\UserService\UserNotFoundException;
use App\Repository\UserRepositoryInterface;
use App\Service\UserService;
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

        $userService = new UserService($userRepository);
        $userService->getById($id);
    }

    public function testListWithSuccess(): void
    {
        $usersExpected = [
            (new User())
                ->setId(1)
                ->setName('John Doe')
                ->setEmail('john@randommail.local')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(2)
                ->setName('Jane Smith Johnson')
                ->setEmail('jane@webmail.dev')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
            (new User())
                ->setId(3)
                ->setName('Michael Brown')
                ->setEmail('michael@techcorp.localhost')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(4)
                ->setName('Emily Davis')
                ->setEmail('emily.d@internetprovider.local')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
            (new User())
                ->setId(5)
                ->setName('William Roberts')
                ->setEmail('william@fastmail.dev')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(6)
                ->setName('Jessica Wilson Thompson')
                ->setEmail('jessica@supermail.local')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
            (new User())
                ->setId(7)
                ->setName('David Miller')
                ->setEmail('david@companymail.localhost')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(8)
                ->setName('Sarah Taylor Green')
                ->setEmail('sarah@personalmail.dev')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
            (new User())
                ->setId(9)
                ->setName('Christopher Anderson Lee')
                ->setEmail('chris.anderson@freemail.local')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(10)
                ->setName('Amanda Thompson Silva')
                ->setEmail('amanda@customdomain.localhost')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
        ];

        $userRepository = Mockery::mock(UserRepositoryInterface::class);
        $userRepository
            ->shouldReceive('list')
            ->withArgs([Mockery::type('null'), Mockery::type('null')])
            ->andReturn($usersExpected)
        ;

        $userService = new UserService($userRepository);
        $usersGot = $userService->list();

        self::assertEquals($usersExpected, $usersGot);
    }
}
