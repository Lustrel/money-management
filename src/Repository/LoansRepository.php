<?php

namespace App\Repository;

use App\Entity\Loan as LoanEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Loan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loan[]    findAll()
 * @method Loan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoansRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LoanEntity::class);
    }

    public function filter($data)
    {
        $qb = $this->createQueryBuilder('l');
        if(!is_null($data['filterName']))
        {
            $qb->leftJoin('l.customer', 'c')
            ->where('c.name = :name')
            ->setParameter('name', $data['filterName']);
        }
        if(!is_null($data['filterBorrowedValue']))
        {
            $qb->andWhere('l.borrowed_value = :borrowed_value')
            ->setParameter('borrowed_value', $data['filterBorrowedValue']);
        }
        
        return $qb->getQuery()->getResult();
    }
}
