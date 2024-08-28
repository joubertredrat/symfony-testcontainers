<?php

declare(strict_types = 1);

namespace App\Tests\Integration\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Integration\IntegrationTestCase;

class UserRepositoryTest extends IntegrationTestCase
{
    public function testSave(): void
    {
        $user = new User();
        $user->setName('John Doe');
        $user->setEmail('john@doe.local');

        $repo = $this->getRepository();
        $repo->save($user, true);

        self::assertNotNull($user->getId());
        self::assertIsInt($user->getId());
        self::assertTrue($user->getId() > 0);
    }

    public function testGetByEmail(): void
    {
        $user = new User();
        $user->setName('John Doe');
        $user->setEmail('john2@doe.local');

        $repo = $this->getRepository();
        $userNotFound = $repo->getByEmail($user->getEmail());
        self::assertNull($userNotFound);

        $repo->save($user, true);
        $userFound = $repo->getByEmail($user->getEmail());

        self::assertEquals($user->getEmail(), $userFound->getEmail());
    }

    public function testGetById(): void
    {
        $repo = $this->getRepository();
        $userNotFound = $repo->getById(1);
        self::assertNull($userNotFound);

        $user = new User();
        $user->setName('John Doe');
        $user->setEmail('john2@doe.local');
        $repo->save($user, true);
        $userFound = $repo->getById($user->getId());

        self::assertEquals($user->getEmail(), $userFound->getEmail());
    }

    public function testList(): void
    {
        $usersToCreate = [
            (new User())->setName('John Doe')->setEmail('john@randommail.local'),
            (new User())->setName('Jane Smith Johnson')->setEmail('jane@webmail.dev'),
            (new User())->setName('Michael Brown')->setEmail('michael@techcorp.localhost'),
            (new User())->setName('Emily Davis')->setEmail('emily.d@internetprovider.local'),
            (new User())->setName('William Roberts')->setEmail('william@fastmail.dev'),
            (new User())->setName('Jessica Wilson Thompson')->setEmail('jessica@supermail.local'),
            (new User())->setName('David Miller')->setEmail('david@companymail.localhost'),
            (new User())->setName('Sarah Taylor Green')->setEmail('sarah@personalmail.dev'),
            (new User())->setName('Christopher Anderson Lee')->setEmail('chris.anderson@freemail.local'),
            (new User())->setName('Amanda Thompson Silva')->setEmail('amanda@customdomain.localhost'),
        ];

        $repo = $this->getRepository();
        foreach ($usersToCreate as $user) {
            $repo->save($user, true);
        }

        $usersList = $repo->list();
        self::assertCount(10, $usersList);

        $usersList = $repo->list('Thompson');
        self::assertCount(2, $usersList);

        $usersList = $repo->list('', '.local');
        self::assertCount(7, $usersList);

        $usersList = $repo->list('Foo', 'foo@bar.local');
        self::assertCount(0, $usersList);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this
            ->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getConnection()
        ;
        $connection->executeStatement('TRUNCATE TABLE users');
    }

    protected function getRepository(): UserRepository
    {
        return $this->getContainer()->get(UserRepository::class);
    }
}
