<?php
namespace App\Controller;

use App\Entity\Customer as CustomerEntity;
use App\Entity\InstallmentPeriod as InstallmentPeriodEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\Loan as LoanEntity;
use App\Entity\Installment as InstallmentEntity;
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
    public function index(Request $request)
    {
        $loans = $this
            ->getDoctrine()
            ->getRepository(LoanEntity::class)
            ->findAll();

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
            $data = $form->getData();

            $filterLoans = $this->getDoctrine()
                ->getRepository(LoanEntity::class)
                ->filterLoan($data);

            if($filterLoans == null){
                $this->addFlash(
                    'notice',
                    'Não há registros com esses dados!'
                );
            }else{
                $loans = $filterLoans;
            }
        }

        return $this->render('loan/index.html.twig', array(
            'loans' => $loans,
            'filter_form' => $form->createView()
        ));
    }

    public function new(Request $request)
    {
        $loan = new LoanEntity();
        $loan->setMonthlyFee(20);

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
            $loan = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $borrowedValue = $loan->getBorrowedValue();
            $monthlyFee = $loan->getMonthlyFee();
            $discount = $loan->getDiscount();
            $totalInstallments = $loan->getTotalInstallments();
            $firstInstallmentDate = $form->get('installments')->getData();

            $calcMonthlyFee = ($monthlyFee * $borrowedValue) / 100;
            $calcDiscount = ($discount * $borrowedValue) / 100;
            $calcBorrowedValue = ($borrowedValue + $calcMonthlyFee) - $calcDiscount;
            $calcInstallmentValues = $calcBorrowedValue / $totalInstallments;

            $periodMap = array(
                '1' => '+1 day',
                '2' => '+7 day',
                '3' => '+15 day',
                '4' => '+1 month',
            );
            $installmentPeriod = $periodMap[$loan->getInstallmentPeriod()->getId()];

            $entityManager->persist($loan);

            for($i = 0; $i < $totalInstallments; $i++)
            {
                $installment = (new InstallmentEntity())
                ->setValue($calcInstallmentValues)
                ->setStatus($this->getDoctrine()->getRepository(InstallmentStatusEntity::class)->findOneBy(array('id' => 1)))
                ->setLoan($loan)
                ->setDueDate($firstInstallmentDate);

                $entityManager->persist($installment);
                $entityManager->flush();
                $firstInstallmentDate = $firstInstallmentDate->modify($installmentPeriod);
            }

            $this->addFlash(
                'notice',
                'Produto cadastrado com sucesso!'
            );

            return $this->redirectToRoute('loans');
        }

        return $this->render('loan/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function edit(Request $request, $id)
    {
        $loan = $this
            ->getDoctrine()
            ->getRepository(LoanEntity::class)
            ->findOneBy(array(
                'id' => $id
            ));

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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Dados do produto foram alterados com sucesso!'
            );

            return $this->redirectToRoute('loans');
        }

        return $this->render('loan/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function remove(Request $resquest, $id)
    {
        $loan = $this
            ->getDoctrine()
            ->getRepository(LoanEntity::class)
            ->findOneBy(array('id' => $id));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($loan);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Produto removido com sucesso!'
        );

        return $this->redirectToRoute('loans');
    }

}
