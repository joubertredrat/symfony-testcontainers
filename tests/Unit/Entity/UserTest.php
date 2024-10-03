<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use App\Exception\Entity\User\InvalidEmailException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected const CANONICAL_FORMAT = 'Y-m-d H:i:s';

    public function testAttributes(): void
    {
        $user = new User();

        self::assertNull($user->getId());
        self::assertNull($user->getName());
        self::assertNull($user->getEmail());
        self::assertNull($user->getCreatedAt());
        self::assertNull($user->getCreatedAtString());
        self::assertNull($user->getUpdatedAt());
        self::assertNull($user->getUpdatedAtString());

        $idExpected = 10;
        $nameExpected = 'John Doe';
        $emailExpected = 'john@doe.local';

        $user->setId($idExpected);
        $user->setName($nameExpected);
        $user->setEmail($emailExpected);
        $user->setCreatedAtNow();
        $user->setUpdatedAtNow();

        self::assertEquals($idExpected, $user->getId());
        self::assertEquals($nameExpected, $user->getName());
        self::assertEquals($emailExpected, $user->getEmail());
        self::assertInstanceOf(DateTimeImmutable::class, $user->getCreatedAt());
        self::assertInstanceOf(DateTimeImmutable::class, $user->getUpdatedAt());

        $createdAtExpected = new DateTimeImmutable('2024-08-26 21:06:28');
        $updatedAtExpected = new DateTimeImmutable('2024-08-26 21:07:03');
        $user->setCreatedAt($createdAtExpected);
        $user->setUpdatedAt($updatedAtExpected);

        self::assertEquals($createdAtExpected, $user->getCreatedAt());
        self::assertEquals($createdAtExpected->format(self::CANONICAL_FORMAT), $user->getCreatedAtString());
        self::assertEquals($updatedAtExpected, $user->getUpdatedAt());
        self::assertEquals($updatedAtExpected->format(self::CANONICAL_FORMAT), $user->getUpdatedAtString());
    }

    public function testAttributesWithInvalidEmail(): void
    {
        $this->expectException(InvalidEmailException::class);

        (new User)->setEmail('johndoe.local');
    }
}
