<?php
namespace App\Controller;

use App\Entity\Customer as CustomerEntity;
use App\Entity\InstallmentPeriod as InstallmentPeriodEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\Loan as LoanEntity;
use App\Entity\Installment as InstallmentEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Collections\ArrayCollection;
use \DateTime;

class Loan extends AbstractController
{
    public function index(Request $request)
    {
        $loans = $this
            ->getDoctrine()
            ->getRepository(LoanEntity::class)
            ->findAll();

        return $this->render('loans.html.twig', array(
            'loans' => $loans,
        ));
    }

    public function new(Request $request)
    {
        $loan = new LoanEntity();

        $form = $this->createFormBuilder($loan)
            ->add('customer', EntityType::class, array(
                'label' => 'Cliente',
                'class' => CustomerEntity::class,
                'choice_label' => 'name'
            ))
            ->add('borrowedValue', NumberType::class, ['label' => "Valor do empréstimo (R$)"])
            ->add('totalInstallments', NumberType::class, ['label' => "Qntd. de parcelas"])
            ->add('monthlyFee', NumberType::class, ['label' => 'Taxa de juros mensal (%)'])
            ->add('discount', NumberType::class, ['label' => 'Desconto (%)'])
            ->add('installments', DateType::class, array('label' => 'Data primeira parcela', 'mapped' => false))
            ->add('installmentPeriod', EntityType::class, [
                'label' => 'Intervalo de pagamento',
                'class' => InstallmentPeriodEntity::class,
                'choice_label' => 'name'
            ])
            ->add('comments', TextareaType::class, ['label' => 'Observações'])
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
            $entityManager->flush();
        }

        return $this->render('create-loan.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}