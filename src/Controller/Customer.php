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
use App\Service\Customer as CustomerService;


class Customer extends AbstractController
{

    /**
     * @var CustomerService $customerService
     */
    private $customerService;

    /**
     * Construct.
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * 
     */
    public function index(Request $request)
    {
        $customers = $this->customerService->findAll();

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
            $customers = $this->handleFilterFormSubmission($form);
        }

        return $this->render('customer/index.html.twig', array(
            'customers' => $customers,
            'filter_form' => $form->createView()
        ));
    }

    /**
     *
     */
    private function handleFilterFormSubmission($form)
    {
        $data = $form->getData();
        $customers = $this->customerService->filter($data);

        return ($customers == null) ? array() : $customers;
    }

    /**
     * 
     */
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
                'attr' => ['class' => 'select2'],
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
            return $this->handleCreationFormSubmission($form);
        }

        return $this->render('customer/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *  
     */
    private function handleCreationFormSubmission($form)
    {
        $customer = $form->getData();

        $this->customerService->create($customer);

        $this->addFlash(
            'notice',
            'Cliente cadastrado com sucesso!'
        );

        return $this->redirectToRoute('customers');
    }

    /**
     * 
     */
    public function edit(Request $request, $id)
    {
        $customer = $this->customerService->findById($id);

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
            return $this->handleEditFormSubmission($form);
        }

        return $this->render('customer/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *
     */
    private function handleEditFormSubmission()
    {
        $this->customerService->update();

        $this->addFlash(
            'notice',
            'Dados do cliente foram alterados com sucesso!'
        );

        return $this->redirectToRoute('customers');
    }

    /**
     * 
     */
    public function remove(Request $request, $id)
    {
        $customer = $this->customerService->findById($id);

        $this->customerService->remove($id);

        $this->addFlash(
            'notice',
            'Cliente removido com sucesso!'
        );

        return $this->redirectToRoute('customers');
    }
}
