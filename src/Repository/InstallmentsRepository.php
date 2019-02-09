<?php

namespace App\Repository;

use App\Entity\Customer as CustomerEntity;
use App\Entity\Installment as InstallmentEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\Loan as LoanEntity;
use App\Entity\User as UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InstallmentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InstallmentEntity::class);
    }

    public function findNext(InstallmentEntity $installment)
    {
        $id = $installment->getId();
        return $this->findOneBy(array('id' => ($id + 1)));
    }

    public function findAllPaid()
    {
        return $this->findBy(array('status' => InstallmentStatusEntity::$ID_PAID));
    }

    public function findAllSortedByDueDate()
    {
        return $this->findBy([], ['due_date' => 'ASC']);
    }

    public function findByNameValue($data)
    {
        $qb = $this->createQueryBuilder('i')
            ->join('i.loan', 'l')
            ->join('l.customer', 'c');

        if (!is_null($data['filterName'])) {
            $qb
                ->where('c.name LIKE :name')
                ->setParameter('name', '%'.$data['filterName'].'%');
        }

        if (!is_null($data['filterValue'])) {
            $qb
                ->andWhere('i.value = :value')
                ->setParameter('value', $data['filterValue']);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByUserAndDate(UserEntity $user, $date)
    {
        $qb = $this
            ->createQueryBuilder('i')
            ->innerJoin('i.loan', 'l', 'WITH', 'i.loan = l.id')
            ->innerJoin('l.customer', 'c', 'WITH', 'l.customer = c.id')
            ->innerJoin('c.user', 'u', 'WITH', 'c.user = u.id')
            ->where('u.id = :user_id')
            ->andWhere('i.due_date = :date')
            ->orderBy('i.due_date', 'ASC');

        $qb->setParameters([
            'user_id' => $user,
            'date' => $date,
        ]);

        return $qb->getQuery()->getResult();
    }

    public function findAllByLoan($loanId) 
    {
        return $this->findBy(['loan' => $loanId]);
    }

    public function findByUserAndLoan(UserEntity $user, $loanId)
    {
        $qb = $this
            ->createQueryBuilder('i')
            ->innerJoin('i.loan', 'l', 'WITH', 'i.loan = l.id')
            ->innerJoin('l.customer', 'c', 'WITH', 'l.customer = c.id')
            ->innerJoin('c.user', 'u', 'WITH', 'c.user = u.id')
            ->where('u.id = :user_id')
            ->andWhere('l.id = :loan_id');

        $qb->setParameters([
            'user_id' => $user,
            'loan_id' => $loanId,
        ]);

        return $qb->getQuery()->getResult();
    }
}
