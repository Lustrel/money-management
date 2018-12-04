<?php

namespace App\Repository;

use App\Entity\Loans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Loans|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loans|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loans[]    findAll()
 * @method Loans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoansRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Loans::class);
    }

    // /**
    //  * @return Loans[] Returns an array of Loans objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Loans
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
