<?php

namespace App\Repository;

use App\Entity\User as UserEntity;
use App\Entity\Role as RoleEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }

    public function updateUserPassword(UserEntity $user, $password)
    {
        $qb = $this->createQueryBuilder('u')
        ->update(UserEntity::class, 'u')
        ->set('u.password', ':password')
        ->where('u.id = :id')
        ->setParameter(':password', $password)
        ->setParameter(':id', $user->getId())
        ->getQuery()
        ->execute();
    }
}
