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
}
