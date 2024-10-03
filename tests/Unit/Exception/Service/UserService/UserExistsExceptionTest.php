<?php

declare(strict_types=1);

namespace App\Tests\Unit\Exception\Service\UserService;

use App\Exception\Service\UserService\UserExistsException;
use PHPUnit\Framework\TestCase;

class UserExistsExceptionTest extends TestCase
{
    public function testThrowException(): void
    {
        $this->expectException(UserExistsException::class);
        $this->expectExceptionMessage('User with e-mail already registered [ john@doe.local ]');

        throw UserExistsException::create('john@doe.local');
    }
}
