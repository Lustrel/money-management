<?php
namespace App\Controller;

use App\Entity\Installment as InstallmentEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\User as UserEntity;
use App\Service\Installment as InstallmentService;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Installment extends Controller
{
    /**
     * @var InstallmentService $installmentService
     */
    private $installmentService;

    /**
     * Construct.
     */
    public function __construct(InstallmentService $installmentService)
    {
        $this->installmentService = $installmentService;
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $installments = $this->installmentService->findAll();

        return $this->render('installment/index.html.twig', array(
            'installments' => $installments,
        ));
    }

    /**
     *
     */
    public function pay(Request $request, $id)
    {
        $installment = $this->installmentService->findById($id);

        $form = $this->createFormBuilder()
            ->add('payment', NumberType::class, array(
                'label' => 'Valor recebido',
                'attr' => array('value' => $installment->getValue()),
                'mapped' => false
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Dar baixa',
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handlePaymentFormSubmission($form, $installment);
        }

        return $this->render('installment/pay.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     *
     */
    private function handlePaymentFormSubmission($form, $installment)
    {
        $paidValue = $form->get('payment')->getData();
        $currentValue = $installment->getValue();

        $this->installmentService->pay($installment, $paidValue);

        if ($this->installmentService->isPartialPayment($currentValue, $paidValue)) {
            $next = $this->installmentService->findNext($installment);

            if ($next) {
                $this->installmentService->updateWithInterest($next, ($currentValue - $paidValue));
            } else {
                return $this->render('installment/new-loan-message.html.twig', array(
                    'value' => ($currentValue - $paidValue),
                    'customer' => $installment->getLoan()->getCustomer(),
                ));
            }
        }

        $this->addFlash(
            'installment#paid_successfully',
            'Baixa de parcela efetuada com sucesso!'
        );

        return $this->redirectToRoute('installments');
    }
}
