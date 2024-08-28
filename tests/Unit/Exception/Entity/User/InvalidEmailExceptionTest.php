<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Exception\Entity\User;

use App\Exception\Entity\User\InvalidEmailException;
use PHPUnit\Framework\TestCase;

class InvalidEmailExceptionTest extends TestCase
{
    public function testThrowException(): void
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessage('Invalid e-mail got [ johndoe.local ]');

        throw InvalidEmailException::create('johndoe.local');
    }
}
