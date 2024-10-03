<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CreateUser;
use App\Entity\User;
use App\Exception\Service\UserService\UserExistsException;
use App\Exception\Service\UserService\UserNotFoundException;
use App\Repository\UserRepositoryInterface;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
    }

    public function create(CreateUser $createUser): User
    {
        $userFound = $this
            ->userRepository
            ->getByEmail($createUser->email)
        ;

        if ($userFound instanceof User) {
            throw UserExistsException::create($createUser->email);
        }

        $user = new User();
        $user->setName($createUser->name);
        $user->setEmail($createUser->email);
        $this
            ->userRepository
            ->save($user, true)
        ;

        return $user;
    }

    public function getById(int $id): User
    {
        $user = $this
            ->userRepository
            ->getById($id)
        ;
        if (!$user instanceof User) {
            throw UserNotFoundException::create($id);
        }

        return $user;
    }

    public function list(?string $nameCriteria = null, ?string $emailCriteria = null): array
    {
        return $this
            ->userRepository
            ->list($nameCriteria, $emailCriteria)
        ;
    }
}
