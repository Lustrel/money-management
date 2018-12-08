<?php

namespace App\Repository;

use App\Entity\InstallmentPeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InstallmentPeriod|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstallmentPeriod|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstallmentPeriod[]    findAll()
 * @method InstallmentPeriod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstallmentPeriodsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InstallmentPeriod::class);
    }

    // /**
    //  * @return InstallmentPeriod[] Returns an array of InstallmentPeriod objects
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
    public function findOneBySomeField($value): ?InstallmentPeriod
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
