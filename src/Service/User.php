<?php
namespace App\Service;

use App\Entity\User as UserEntity;
use App\Repository\UserRepository as UserRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class User
{
    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var CustomerRepository $customerRepository
     */
    private $customerRepository;

    /**
     * Construct.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository(UserEntity::class);
    }

    /**
     * 
     */
    public function update(UserEntity $user, $password, $encoder)
    {
        $user->setPassword($encoder->encodePassword($user, $password));
        $this->entityManager->flush();
    }

}
