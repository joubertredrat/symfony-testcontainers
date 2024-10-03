<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $entity, bool $flush = false): void;

    public function getByEmail(string $email): ?User;

    public function getById(int $id): ?User;

    public function list(?string $nameCriteria = null, ?string $emailCriteria = null): array;
}
