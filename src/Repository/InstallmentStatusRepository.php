<?php

namespace App\Repository;

use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class InstallmentStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InstallmentStatusEntity::class);
    }

    public function getToReceive()
    {
        return $this->find(InstallmentStatusEntity::$ID_TO_RECEIVE);
    }

    public function getPaid()
    {
        return $this->find(InstallmentStatusEntity::$ID_PAID);
    }

    public function getInArrears()
    {
        return $this->find(InstallmentStatusEntity::$ID_IN_ARREARS);
    }
}
