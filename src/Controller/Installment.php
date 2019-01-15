<?php
namespace App\Controller;

use App\Entity\Installment as InstallmentEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\User as UserEntity;
use App\Service\Installment as InstallmentService;
use App\Entity\Helper as HelperEntity;
use App\Service\Helper as HelperService;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
     * @var HelperService $helperService
     */
    private $helperService;

    /**
     * Construct.
     */
    public function __construct(
        InstallmentService $installmentService,
        HelperService $helperService
    )
    {
        $this->installmentService = $installmentService;
        $this->helperService = $helperService;
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $this->helperService->checkLastInstallmentActualization();        
        
        $installments = $this->installmentService->findByRole(
            $this->isGranted('ROLE_ADMIN')
        );

        $form = $this->createFormBuilder()
            ->add('filterName', TextType::class, array(
                'label' => 'Cliente',
                'required' => false,
            ))
            ->add('filterValue', TextType::class, array(
                'label' => 'Valor',
                'required' => false,
            ))
            ->add('submit', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $installments = $this->handleFilterFormSubmission($form);
        }

        return $this->render('installment/index.html.twig', array(
            'installments' => $installments,
            'filter_form' => $form->createView()
        ));
    }

    /**
     * 
     */
    public function handleFilterFormSubmission($form)
    {
        $data = $form->getData();
        $installments = $this->installmentService->filter($data);

        return ($installments == null) ? array() : $installments;
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
