<?php

namespace App\Controller;

use App\Entity\Customers as CustomerEntity;
use App\Entity\Users as UserEntity;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Customer extends AbstractController
{

    public function customers(Request $request)
    {
        $customers = $this->getDoctrine()->getRepository('App:Customers')->findAll();
        return $this->render('customer/index.html.twig', array('customers' => $customers));
    }

    public function new(Request $request)
    {

        $customer = new CustomerEntity();

        $form = $this->createFormBuilder($customer)
            ->add('name', TextType::class, ['label' => "Nome"])
            ->add('document_number', TextType::class, ['label' => "Número documento"])
            ->add('email', EmailType::class, ['label' => "E-mail"])
            ->add('phone', TextType::class, ['label' => "Telefone"])
            ->add('user',EntityType::class, array('class' => UserEntity::class, 'choice_label' => 'email', 'label' => "Vendedor"))
            ->add('save', SubmitType::class, array('label' => 'Cadastrar'))
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $customer = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('customers');
        }

        return $this->render('customer/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}