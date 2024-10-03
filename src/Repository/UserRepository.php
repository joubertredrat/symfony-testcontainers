<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function getById(int $id): ?User
    {
        return $this->find($id);
    }

    public function list(?string $nameCriteria = null, ?string $emailCriteria = null): array
    {
        $queryBuilder = $this->createQueryBuilder('u')->orderBy('u.name', 'ASC');

        if (!empty($nameCriteria)) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('u.name', ':name'))
                ->setParameter('name', '%' . $nameCriteria . '%')
            ;
        }
        if (!empty($emailCriteria)) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('u.email', ':email'))
                ->setParameter('email', '%' . $emailCriteria . '%')
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
