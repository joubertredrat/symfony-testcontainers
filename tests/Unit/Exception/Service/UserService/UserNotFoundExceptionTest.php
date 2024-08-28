<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Exception\Service\UserService;

use App\Exception\Service\UserService\UserNotFoundException;
use PHPUnit\Framework\TestCase;

class UserNotFoundExceptionTest extends TestCase
{
    public function testThrowException(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User with id not found [ 10 ]');

        throw UserNotFoundException::create(10);
    }
}
