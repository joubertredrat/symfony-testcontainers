<?php

declare(strict_types=1);

namespace App\Tests\Unit\Response;

use App\Entity\User;
use App\Response\UserResponse;
use PHPUnit\Framework\TestCase;

class UserResponseTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $user = (new User())
            ->setId(1)
            ->setName('John Doe')
            ->setEmail('john@doe.local')
            ->setCreatedAtNow()
        ;

        $arrayExpected = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAtString(),
            'updatedAt' => $user->getUpdatedAtString(),
        ];

        $userResponse = new UserResponse($user);
        self::assertEquals($arrayExpected, $userResponse->jsonSerialize());
    }
}
