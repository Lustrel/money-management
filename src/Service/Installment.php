<?php
namespace App\Service;

use App\Entity\Installment as InstallmentEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\Loan as LoanEntity;
use App\Repository\InstallmentsRepository as InstallmentRepository;
use App\Repository\InstallmentStatusRepository;
use App\Service\Calculator as CalculatorService;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class Installment
{
    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var InstallmentRepository $installmentRepository
     */
    private $installmentRepository;

    /**
     * @var InstallmentStatusRepository $installmentStatusRepository
     */
    private $installmentStatusRepository;

    /**
     * @var CalculatorService $calculatorService
     */
    private $calculatorService;

    /**
     * Construct.
     */
    public function __construct(
        EntityManager $entityManager,
        CalculatorService $calculatorService
    )
    {
        $this->entityManager = $entityManager;
        $this->installmentRepository = $entityManager->getRepository(InstallmentEntity::class);
        $this->installmentStatusRepository = $entityManager->getRepository(InstallmentStatusEntity::class);
        $this->calculatorService = $calculatorService;
    }

    /**
     * Find all created installments.
     */
    public function findAll()
    {
        return $this->installmentRepository->findAll();
    }

    /**
     * Find a specific installment by the given id.
     */
    public function findById($id)
    {
        return $this->installmentRepository->find($id);
    }

    /**
     * Find all installments by the given status.
     */
    public function findByStatus(InstallmentStatusEntity $status)
    {
        return $this->installmentRepository->findBy(['status' => $status]);
    }

    /**
     *
     */
    public function findNext(InstallmentEntity $installment)
    {
        return $this->installmentRepository->findNext($installment);
    }

    /**
     * Set the given installment as paid.
     */
    public function pay(InstallmentEntity $installment, $paidValue)
    {
        $paidStatus = $this->installmentStatusRepository->getPaid();
        $installment->setStatus($paidStatus);
        $installment->setValue($paidValue);

        $this->entityManager->persist($installment);
        $this->entityManager->flush();
    }

    /**
     *
     */
    public function isPartialPayment($value, $paidValue)
    {
        return ($value > $paidValue);
    }

    /**
     *
     */
    public function updateWithInterest($installment, $remainingValue)
    {
        // Remaining values suffer a 5% increase
        $fee = 5;

        $valueWithInterest = $this
            ->calculatorService
            ->applyInterestFee($remainingValue, $fee);

        $installment->setValue($installment->getValue() + $valueWithInterest);

        $this->entityManager->persist($installment);
        $this->entityManager->flush();
    }

    /**
     * 
     */
    public function updateInstallmentsInArrears($today)
    {
        $fee = 5;

        $toReceiveInstallments = $this->findByStatus(
            $this->installmentStatusRepository->getToReceive()
        );

        foreach ($toReceiveInstallments as $installment) {
           $installmentDueDate = $installment->getDueDate();
           if($today > $installmentDueDate)
           {
                $valueWithInterest = $this
                    ->calculatorService
                    ->applyInterestFee($installment->getValue(), $fee);

                $installment->setValue($valueWithInterest);
                $installment->setStatus(
                   $this->installmentStatusRepository->getInArrears());

                $this->entityManager->persist($installment);
                $this->entityManager->flush();
           }
        }
    }

    /**
     * 
     */
    public function filter($data)
    {
        return $this->installmentRepository->findByNameValue($data);
    }
}
