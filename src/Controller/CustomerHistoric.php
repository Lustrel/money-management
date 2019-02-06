<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Loan as LoanService;

class CustomerHistoric extends AbstractController
{
    /**
     * @var LoanService $loanService
     */
    private $loanService;

    /**
     * Construct.
     */
    public function __construct(
        LoanService $loanService
    )
    {
        $this->loanService = $loanService;
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $historics = $this->loanService->customerLoansHistoric();
        return $this->render('customer_historic/index.html.twig', array(
            'historics' => $historics,
        ));
    }
}
