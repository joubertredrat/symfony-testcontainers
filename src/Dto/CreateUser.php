<?php

declare(strict_types=1);

namespace App\Dto;

class CreateUser
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
    ) {
    }
}
