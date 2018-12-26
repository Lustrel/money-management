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
    /**
     * Construct.
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InstallmentEntity::class);
    }

    /**
     *
     */
    public function findNext(InstallmentEntity $installment)
    {
        $id = $installment->getId();
        return $this->findOneBy(array('id' => ($id + 1)));
    }

    /*public function findBySellerUser(UserEntity $user)
    {
        return $this->createQueryBuilder('i')
            ->innerJoin(InstallmentStatusEntity::class, 'ist', 'WITH', 'i.status = ist.id')
            ->innerJoin(LoanEntity::class, 'l', 'WITH', 'i.loan = l.id')
            ->innerJoin(CustomerEntity::class, 'c', 'WITH', 'l.customer = c.id')
            ->innerJoin(UserEntity::class, 'u', 'WITH', 'c.user = u.id')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getResult();
    }*/
}
