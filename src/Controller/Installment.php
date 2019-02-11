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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class Installment extends AbstractController
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
     * @var InstallmentStatusRepository $installmentStatusRepository
     */
    private $installmentStatusRepository;

    /**
     * Construct.
     */
    public function __construct(
        EntityManager $entityManager,
        InstallmentService $installmentService,
        HelperService $helperService
    )
    {
        $this->installmentService = $installmentService;
        $this->helperService = $helperService;
        $this->installmentStatusRepository = $entityManager->getRepository(InstallmentStatusEntity::class);
    }

    public function index(Request $request, $loanId = null)
    {
        $this->helperService->checkLastInstallmentActualization();

        if ($loanId) {
            $installments = $this->installmentService->findByRoleAndLoan(
                $this->getUser(),
                $this->isGranted('ROLE_ADMIN'),
                $loanId
            );
        } else {
            $installments = $this->installmentService->findByRole(
                $this->getUser(),
                $this->isGranted('ROLE_ADMIN')
            );
        }

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
        $today = new \DateTime();

        $form = $this->createFormBuilder()
            ->add('paymentDate', DateType::class, array(
                'widget' => 'single_text',
                'label' => 'Data de pagamento',
                'attr' => array('value' => $today->format('Y-m-d')),
                'mapped' => false,
            ))
            ->add('paymentValue', NumberType::class, array(
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
        $paidDate = $form->get('paymentDate')->getData();
        $paidValue = $form->get('paymentValue')->getData();
        $currentValue = $installment->getValue();
        $installment->setPayDate($paidDate);

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

    /**
     *
     */
    public function edit(Request $request, $id)
    {
        $installment = $this->installmentService->findById($id);
        dump($installment);

        $form = $this->createFormBuilder($installment)
        ->add('pay_date', DateType::class, array(
            'widget' => 'single_text',
            'label' => 'Data de pagamento',
            ))
            ->add('value', NumberType::class, array(
                'label' => 'Valor da parcela',
            ))
            ->add('status',EntityType::class, array(
                'label' => 'Status',
                'class' => InstallmentStatusEntity::class,
                'choice_label' => 'name',
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Editar',
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleEditFormSubmission($form);
        }

        return $this->render('installment/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *
     */
    private function handleEditFormSubmission()
    {
        $this->installmentService->update();

        $this->addFlash(
            'installment#success',
            'Dados da parcela foram alterados com sucesso!'
        );

        return $this->redirectToRoute('installments');
    }
}
