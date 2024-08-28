<?php

declare(strict_types = 1);

namespace App\Exception\Service\UserService;

use RuntimeException;

class UserExistsException extends RuntimeException
{
    public static function create(string $email): self
    {
        return new self(sprintf('User with e-mail already registered [ %s ]', $email));
    }
}
