<?php
namespace App\Controller;

use App\Entity\Customer as CustomerEntity;
use App\Entity\InstallmentPeriod as InstallmentPeriodEntity;
use App\Entity\Loan as LoanEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
            ->add('installmentPeriod', EntityType::class, array(
                'label' => 'Intervalo de pagamento',
                'class' => InstallmentPeriodEntity::class,
                'choice_label' => 'name'
            ))
            ->add('discount', NumberType::class, ['label' => 'Desconto (%)'])
            ->add('save', SubmitType::class, array('label' => 'Cadastrar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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