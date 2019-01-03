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

    /**
     * 1. Pegar todos os Installments que possuem o status "Pago"
     * 2. Subtrair, de cada uma, o valor referente ao empréstimo
     * 3. Dividi-los por meses (jan., fev., etc.)
     */
    public function findAll()
    {
        $installments = $this->installmentRepository->findAllPaid();
        $profitPerMonth = array();

        foreach ($installments as $installment) {
            // $monthIndex: 1 through 12
            $monthIndex = date('n', $installment->getDueDate()->getTimestamp());

            if (!isset($profitPerMonth[$monthIndex])) {
                $profitPerMonth[$monthIndex] = 0;
            }

            $profitPerMonth[$monthIndex] += $installment->getValue();
        }

        $finalResult = array();

        foreach ($profitPerMonth as $monthIndex => $profit) {
            array_push($finalResult, array(
                'month' => $this->months[$monthIndex],
                'profit' => $profit,
            ));
        }

        return $finalResult;
    }
}
