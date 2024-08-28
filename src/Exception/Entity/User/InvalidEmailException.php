<?php

declare(strict_types = 1);

namespace App\Exception\Entity\User;

use InvalidArgumentException;

class InvalidEmailException extends InvalidArgumentException
{
    public static function create(string $email): self
    {
        return new self(sprintf('Invalid e-mail got [ %s ]', $email));
    }
}
