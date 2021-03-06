<?php

namespace App\Repository;

use App\Entity\Loan as LoanEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\User as UserEntity;

/**
 * @method Loan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loan[]    findAll()
 * @method Loan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoansRepository extends ServiceEntityRepository
{
    private  $filterMap = array(
        'name' => array(
            'join' => "l.customer",
            'where' => "j.name = :name",
        ),
        'borrowed_value' => array(
            'where' => "l.borrowed_value = :borrowed_value",
        ),
    );

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LoanEntity::class);
    }

    public function findAllSortedByCustomer()
    {
        return $this
            ->createQueryBuilder('l')
            ->innerJoin('l.customer', 'c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByUser(UserEntity $user)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->innerJoin('p.customer', 'c', 'WITH', 'p.customer = c.id')
            ->innerJoin('c.user', 'u', 'WITH', 'c.user = u.id')
            ->where('u.id = :user_id');

        $qb->setParameters([
            'user_id' => $user,
        ]);

        return $qb->getQuery()->getResult();
    }

    public function filter($data)
    {
        if(array_key_exists("join", $this->filterMap[$data['filterType']]))
        {
            return $this->createQueryBuilder('l')
            ->leftJoin($this->filterMap[$data['filterType']]['join'], 'j')
            ->where($this->filterMap[$data['filterType']]['where'])
            ->setParameter($data['filterType'], $data['filterText'])
            ->getQuery()
            ->getResult();
        }else
        {
            return $this->createQueryBuilder('l')
            ->where($this->filterMap[$data['filterType']]['where'])
            ->setParameter($data['filterType'], $data['filterText'])
            ->getQuery()
            ->getResult();
        }
    }

    public function customerLoansHistoric()
    {
        return $this->createQueryBuilder('l')
        ->leftJoin('l.customer', 'c')
        ->select('c.name, l.borrowed_value, l.total_installments')
        ->getQuery()
        ->getResult();
    }
}
