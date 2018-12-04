<?php

namespace App\Repository;

use App\Entity\InstallmentPeriods;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InstallmentPeriods|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstallmentPeriods|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstallmentPeriods[]    findAll()
 * @method InstallmentPeriods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstallmentPeriodsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InstallmentPeriods::class);
    }

    // /**
    //  * @return InstallmentPeriods[] Returns an array of InstallmentPeriods objects
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
    public function findOneBySomeField($value): ?InstallmentPeriods
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
