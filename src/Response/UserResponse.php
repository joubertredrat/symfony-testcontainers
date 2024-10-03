<?php

declare(strict_types=1);

namespace App\Response;

use App\Entity\User;
use JsonSerializable;

class UserResponse implements JsonSerializable
{
    public function __construct(public readonly User $user)
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->user->getId(),
            'name' => $this->user->getName(),
            'email' => $this->user->getEmail(),
            'createdAt' => $this->user->getCreatedAtString(),
            'updatedAt' => $this->user->getUpdatedAtString(),
        ];
    }
}
