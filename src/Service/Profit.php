<?php
namespace App\Service;

use App\Entity\Installment as InstallmentEntity;
use App\Entity\Loan as LoanEntity;
use App\Repository\InstallmentsRepository as InstallmentRepository;
use App\Repository\LoansRepository as LoanRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class Profit
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
     * @var LoanRepository $loanRepository
     */
    private $loanRepository;

    /**
     * @var array $months
     */
    private $months = array(
        '1'  => 'Janeiro',
        '2'  => 'Fevereiro',
        '3'  => 'Março',
        '4'  => 'Abril',
        '5'  => 'Maio',
        '6'  => 'Junho',
        '7'  => 'Julho',
        '8'  => 'Agosto',
        '9'  => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro',
    );


    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->installmentRepository = $entityManager->getRepository(InstallmentEntity::class);
        $this->loanRepository = $entityManager->getRepository(LoanEntity::class);
    }

    public function findAll()
    {
        $installments = $this->installmentRepository->findAllPaid();
        $installmentsPerMonth = array();

        foreach ($installments as $installment) {
            // $monthIndex: 1 through 12
            $monthIndex = date('n', $installment->getDueDate()->getTimestamp());

            if (!isset($installmentsPerMonth[$monthIndex])) {
                $installmentsPerMonth[$monthIndex] = array();
            }

            array_push($installmentsPerMonth[$monthIndex], $installment);
        }

        $formattedData = array();

        foreach ($installmentsPerMonth as $monthIndex => $installments) {
            array_push($formattedData, array(
                'month' => $this->months[$monthIndex],
                'year' => date('Y', $installments[0]->getDueDate()->getTimestamp()),
                'profit' => $this->sumInstallments($installments),
            ));
        }

        return $formattedData;
    }

    private function sumInstallments($installments)
    {
        $sum = 0;
        foreach ($installments as $installment) {
            $sum += $installment->getValue();
        }
        return $sum;
    }
}
