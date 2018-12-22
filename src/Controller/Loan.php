<?php
namespace App\Controller;

use App\Entity\Customer as CustomerEntity;
use App\Entity\InstallmentPeriod as InstallmentPeriodEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\Loan as LoanEntity;
use App\Entity\Installment as InstallmentEntity;
use App\Repository\LoansRepository as LoanRepository;
use App\Service\Loan as LoanService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;

class Loan extends AbstractController
{
    /**
     * @var LoanService $loanService
     */
    private $loanService;

    /**
     * Construct.
     */
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     *
     */
    public function index(Request $request)
    {
        $loans = $this->loanService->findAll();

        $form = $this->createFormBuilder()
            ->add('filterText', TextType::class, ['label' => 'Valor'])
            ->add('filterType', ChoiceType::class, array(
                'label' => "Campo",
                'choices' => array(
                    'Cliente' => 'name',
                    'Valor do produto' => 'borrowed_value',
                ),
            ))
            ->add('submit', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleFilterFormSubmission($form);
        }

        return $this->render('loan/index.html.twig', array(
            'loans' => $loans,
            'filter_form' => $form->createView()
        ));
    }

    /**
     *
     */
    private function handleFilterFormSubmission($form)
    {
        $data = $form->getData();
        $loans = $this->loanService->filter($data);

        return ($loans == null) ? array() : $loans;
    }

    /**
     *
     */
    public function new(Request $request)
    {
        $loan = new LoanEntity();
        $loan->setMonthlyFee(20);
        $loan->setDiscount(0);

        $form = $this->createFormBuilder($loan)
            ->add('customer', EntityType::class, array(
                'label' => 'Cliente',
                'class' => CustomerEntity::class,
                'choice_label' => 'name',
            ))
            ->add('borrowedValue', NumberType::class, ['label' => "Valor do produto (R$)"])
            ->add('totalInstallments', NumberType::class, ['label' => "Número de parcelas"])
            ->add('monthlyFee', NumberType::class, ['label' => 'Taxa de juros total (%)'])
            ->add('discount', NumberType::class, ['label' => 'Desconto no valor total (%)'])
            ->add('comments', TextareaType::class, array(
                'label' => 'Observações',
                'required' => false,
            ))
            ->add('installments', DateType::class, array(
                'widget' => 'single_text',
                'label' => 'Data de vencimento da primeira parcela',
                'mapped' => false,
            ))
            ->add('installmentPeriod', EntityType::class, array(
                'label' => 'Período entre cada parcela',
                'class' => InstallmentPeriodEntity::class,
                'choice_label' => 'name',
            ))
            ->add('save', SubmitType::class, ['label' => 'Cadastrar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleCreationFormSubmission($form);
        }

        return $this->render('loan/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *
     */
    private function handleCreationFormSubmission($form)
    {
        $loan = $form->getData();
        $paymentDate = $form->get('installments')->getData();

        $this->loanService->create($loan, $paymentDate);

        $this->addFlash(
            'success',
            'Produto cadastrado com sucesso!'
        );

        return $this->redirectToRoute('loans');
    }

    /**
     *
     */
    public function edit(Request $request, $id)
    {
        $loan = $this->loanService->findById($id);

        $form = $this->createFormBuilder($loan)
            ->add('customer', EntityType::class, array(
                'label' => 'Cliente',
                'class' => CustomerEntity::class,
                'choice_label' => 'name',
            ))
            ->add('borrowedValue', NumberType::class, ['label' => "Valor do produto (R$)"])
            ->add('totalInstallments', NumberType::class, ['label' => "Qntd. de parcelas"])
            ->add('monthlyFee', NumberType::class, ['label' => 'Taxa de juros mensal (%)'])
            ->add('discount', NumberType::class, ['label' => 'Desconto (%)'])
            ->add('installmentPeriod', EntityType::class, array(
                'label' => 'Intervalo de pagamento',
                'class' => InstallmentPeriodEntity::class,
                'choice_label' => 'name',
            ))
            ->add('comments', TextareaType::class, ['label' => 'Observações'])
            ->add('save', SubmitType::class, ['label' => 'Cadastrar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleEditFormSubmission($form);
        }

        return $this->render('loan/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *
     */
    private function handleEditFormSubmission($form)
    {
        $loan = $form->getData();

        $this->loanService->update($loan);

        $this->addFlash(
            'notice',
            'Dados do produto foram alterados com sucesso!'
        );

        return $this->redirectToRoute('loans');
    }

    /**
     *
     */
    public function remove(Request $request, $id)
    {
        $this->loanService->remove($id);

        $this->addFlash(
            'success',
            'Produto removido com sucesso!'
        );

        return $this->redirectToRoute('loans');
    }

}
