<?php
namespace App\Service;

use App\Entity\Customer as CustomerEntity;
use App\Repository\CustomersRepository as CustomerRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class Customer
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
        $this->customerRepository = $entityManager->getRepository(CustomerEntity::class);
    }

    /**
     *
     */
    public function findAll()
    {
        return $this->customerRepository->findAll();
    }

    /**
     *
     */
    public function findById($id)
    {
        return $this->customerRepository->findOneBy(['id' => $id]);
    }

    /**
     *
     */
    public function filter($data)
    {
        return $this->customerRepository->filter($data);
    }

    /**
     * 
     */
    public function create(CustomerEntity $customer)
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }    

    /**
     * 
     */
    public function update()
    {
        $this->entityManager->flush();
    }

    /**
     *
     */
    public function remove($customerId)
    {
        $customer = $this->customerRepository->findOneBy(['id' => $customerId]);
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }
}
