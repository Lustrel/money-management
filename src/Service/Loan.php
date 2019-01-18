<?php
namespace App\Service;

use App\Entity\Installment as InstallmentEntity;
use App\Entity\InstallmentPeriod as InstallmentPeriodEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\Loan as LoanEntity;
use App\Repository\InstallmentStatusRepository;
use App\Repository\LoansRepository as LoanRepository;
use App\Service\Calculator as CalculatorService;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class Loan
{
    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var LoanRepository $loanRepository
     */
    private $loanRepository;

    /**
     * @var InstallmentStatusRepository $installmentStatusRepository
     */
    private $installmentStatusRepository;

    /**
     * @var CalculatorService $calculatorService
     */
    private $calculatorService;


    public function __construct(
        EntityManager $entityManager,
        CalculatorService $calculatorService
    )
    {
        $this->entityManager = $entityManager;
        $this->loanRepository = $entityManager->getRepository(LoanEntity::class);
        $this->installmentStatusRepository = $entityManager->getRepository(InstallmentStatusEntity::class);
        $this->calculatorService = $calculatorService;
    }

    public function findAll()
    {
        return $this->loanRepository->findAll();
    }

    public function findById($id)
    {
        return $this->loanRepository->findOneBy(['id' => $id]);
    }

    public function filter($data)
    {
        return $this->loanRepository->filter($data);
    }

    public function create(LoanEntity $loan, $paymentDate)
    {
        $borrowed = $loan->getBorrowedValue();
        $fee = $loan->getMonthlyFee();
        $discount = $loan->getDiscount();
        $totalInstallments = $loan->getTotalInstallments();

        $priceWithInterest = $this
            ->calculatorService
            ->applyInterestFee($borrowed, $fee);

        $priceAfterDiscount = $this
            ->calculatorService
            ->applyDiscount($priceWithInterest, $discount);

        $eachInstallmentPrice = ($priceAfterDiscount / $totalInstallments);

        $periods = array(
            InstallmentPeriodEntity::$ID_DAILY => '+1 day',
            InstallmentPeriodEntity::$ID_WEEKLY => '+7 day',
            InstallmentPeriodEntity::$ID_FORTNIGHTLY => '+15 day',
            InstallmentPeriodEntity::$ID_MONTHLY => '+1 month'
        );

        $this->entityManager->persist($loan);
        $this->entityManager->flush();

        for ($i = 0; $i < $totalInstallments; $i++) {
            $this->createInstallment($loan, $eachInstallmentPrice, $paymentDate);
            $paymentDate->modify($periods[$loan->getInstallmentPeriod()->getId()]);
        }
    }

    /**
     * @todo: move it to App\Service\Installment
     */
    private function createInstallment($loan, $price, $paymentDate)
    {
        $toBePaidStatus = $this->installmentStatusRepository->getToReceive();

        $installment = (new InstallmentEntity())
            ->setValue($price)
            ->setStatus($toBePaidStatus)
            ->setLoan($loan)
            ->setDueDate($paymentDate);

        $this->entityManager->persist($installment);
        $this->entityManager->flush();
    }

    public function remove($loanId)
    {
        $loan = $this->loanRepository->findOneBy(['id' => $loanId]);
        $this->entityManager->remove($loan);
        $this->entityManager->flush();
    }

    public function customerLoansHistoric()
    {
        return $this->loanRepository->customerLoansHistoric();
    }
}
