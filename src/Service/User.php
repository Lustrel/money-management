<?php
namespace App\Service;

use App\Entity\User as UserEntity;
use App\Repository\UserRepository as UserRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class User
{
    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;


    public function __construct(
        EntityManager $entityManager,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->userRepository = $entityManager->getRepository(UserEntity::class);
    }

    public function findAll()
    {
        return $this->userRepository->findAll();
    }

    public function findById($id)
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }

    public function update()
    {
        $this->entityManager->flush();
    }

    public function remove($userId)
    {
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function updatePassword(UserEntity $user, $decodedPassword)
    {
        $password = $this->encoder->encodePassword($user, $decodedPassword);
        $this->userRepository->updateUserPassword($user, $password);
    }

    public function filter($data)
    {
        return $this->userRepository->findBy(array(
            $data['filterType'] => $data['filterText']
        ));
    }

    public function create(UserEntity $user, $encoder)
    {
        $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}
