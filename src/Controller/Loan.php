<?php
namespace App\Controller;

use App\Entity\Customer as CustomerEntity;
use App\Entity\InstallmentPeriod as InstallmentPeriodEntity;
use App\Entity\Loan as LoanEntity;
use App\Entity\Installment as InstallmentEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

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
            ->add('installments', DateType::class, ['label' => 'Data primeira parcela'])
            ->add('installmentPeriod', EntityType::class, [
                'label' => 'Intervalo de pagamento',
                'class' => InstallmentPeriodEntity::class,
                'choice_label' => 'name'
            ])
            ->add('comments', TextareaType::class, ['label' => 'Observações'])
            ->add('save', SubmitType::class, ['label' => 'Cadastrar'])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $loan = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();

            $borrowedValue = $loan->getBorrowedValue();
            $monthlyFee = $loan->getMonthlyFee();
            $discount = $loan->getDiscount();
            $totalInstallments = $loan->getTotalInstallments();
            $firstInstallmentDate = $loan->getInstallments();

            switch ($loan->getInstallmentPeriod()->getId()) {
                case 1:
                    $installmentPeriod = "+1 day";
                break;
                case 2:
                    $installmentPeriod = "+7 day";
                break;
                case 3:
                    $installmentPeriod = "+15 day";
                break;
                case 4:
                    $installmentPeriod = "+1 month";
                break;
            }

            $calcMonthlyFee = ($monthlyFee * $borrowedValue) / 100;
            $calcDiscount = ($discount * $borrowedValue) / 100;
            $calcBorrowedValue = ($borrowedValue + $calcMonthlyFee) - $calcDiscount;
            $calcInstallmentValues = $calcBorrowedValue / $totalInstallments;


            // for($i = 0; $i > $totalInstallments; $i++){
            //     if($i == 0){
            //         $installment = (new InstallmentEntity())
            //             
            //         ;
            //     }else{
            //     }
            // }

          
            // echo("quanto ele emprestou ".$borrowedValue);
            // echo("<br>quanto percent clocou de juros ".$monthlyFee);
            // echo("<br>quanto deu os juros ".$calcMonthlyFee);
            // echo("<br>quanto percent clocou de desconto ".$discount);
            // echo("<br>quanto deu o desconto ".$calcDiscount);
            // echo("<br>no final o valor a ser pago pelo cliente é ".$calcBorrowedValue);
            // echo("<br>quantidade de parcelas: ".$totalInstallments);
            // echo("<br>a primeira parcela ele vai pagar em: ".$firstInstallmentDate->format('Y-m-d'));
            // echo("<br>e os valores de cada parcela vai ser: ".round($calcInstallmentValues, 2));
            

            /*
            $loan = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($loan);
            $entityManager->flush();

            return $this->redirectToRoute('users');
            */
        }

        return $this->render('create-loan.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}