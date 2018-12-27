<?php
namespace App\Controller;

use App\Entity\Customer as CustomerEntity;
use App\Entity\User as UserEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class Customer extends AbstractController
{
    public function index(Request $request)
    {
        $customers = $this
            ->getDoctrine()
            ->getRepository(CustomerEntity::class)
            ->findAll();

        $form = $this->createFormBuilder()
            ->add('filterText', TextType::class, ['label' => 'Valor'])
            ->add('filterType', ChoiceType::class, array(
                'label' => "Campo",
                'choices' => array(
                    'Nome' => 'name',
                    'Número Documento' => 'document_number',
                    'E-mail' => 'email',
                ),
            ))
            ->add('submit', SubmitType::class, ['label' => 'Filtrar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $filterCustomers = $this
                ->getDoctrine()
                ->getRepository(CustomerEntity::class)
                ->findBy(array($data['filterType'] => $data['filterText']));

            if($filterCustomers == null){
                $this->addFlash(
                    'notice',
                    'Não há registros com esses dados!'
                );
            }else{
                $customers = $filterCustomers;
            }

        }

        return $this->render('customer/index.html.twig', array(
            'customers' => $customers,
            'filter_form' => $form->createView()
        ));
    }

    public function new(Request $request)
    {
        $customer = new CustomerEntity();

        $form = $this->createFormBuilder($customer)
            ->add('name', TextType::class, ['label' => "Nome completo do cliente"])
            ->add('document_number', TextType::class, ['label' => "Número do documento (CPF ou CNPJ)"])
            ->add('email', EmailType::class, ['label' => "E-mail"])
            ->add('phone', TextType::class, ['label' => "Telefone"])
            ->add('user',EntityType::class, [
                'class' => UserEntity::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('user')
                    ->where('user.role = 3');
                },
                'choice_label' => 'name',
                'label' => "Vendedor responsável",
                'placeholder' => 'Selecione um vendedor',
                'required' => true
            ])
            ->add('save', SubmitType::class, ['label' => 'Cadastrar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            $this->addFlash(
                'customer#success',
                'Cliente cadastrado com sucesso!'
            );

            return $this->redirectToRoute('customers');
        }

        return $this->render('customer/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function edit(Request $request, $id)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(CustomerEntity::class)
            ->findOneBy(array('id' => $id));

        $form = $this->createFormBuilder($customer)
            ->add('name', TextType::class, ['label' => "Nome Completo"])
            ->add('document_number', TextType::class, ['label' => "Número documento"])
            ->add('email', EmailType::class, ['label' => "E-mail"])
            ->add('phone', TextType::class, ['label' => "Telefone"])
            ->add('user',EntityType::class, [
                'class' => UserEntity::class,
                'choice_label' => 'name',
                'label' => "Vendedor",
            ])
            ->add('save', SubmitType::class, ['label' => 'Cadastrar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash(
                'customer#success',
                'Dados do cliente foram alterados com sucesso!'
            );

            return $this->redirectToRoute('customers');
        }

        return $this->render('customer/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function remove(Request $request, $id)
    {
        $customer = $this
            ->getDoctrine()
            ->getRepository(CustomerEntity::class)
            ->findOneBy(array('id' => $id));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($customer);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Cliente removido com sucesso!'
        );

        return $this->redirectToRoute('customers');
    }
}
