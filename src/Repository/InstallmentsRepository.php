<?php

namespace App\Repository;

use App\Entity\Installments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Installments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Installments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Installments[]    findAll()
 * @method Installments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstallmentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Installments::class);
    }

    // /**
    //  * @return Installments[] Returns an array of Installments objects
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
    public function findOneBySomeField($value): ?Installments
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
