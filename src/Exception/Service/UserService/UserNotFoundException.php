<?php

declare(strict_types=1);

namespace App\Exception\Service\UserService;

use RuntimeException;

class UserNotFoundException extends RuntimeException
{
    public static function create(int $id): self
    {
        return new self(sprintf('User with id not found [ %d ]', $id));
    }
}
