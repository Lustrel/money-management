<?php

namespace App\Repository;

use App\Entity\InstallmentStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InstallmentStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstallmentStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstallmentStatus[]    findAll()
 * @method InstallmentStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstallmentStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InstallmentStatus::class);
    }

    // /**
    //  * @return InstallmentStatus[] Returns an array of InstallmentStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InstallmentStatus
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
