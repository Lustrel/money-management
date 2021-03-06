<?php
namespace App\Controller;

use App\Entity\User as UserEntity;
use App\Entity\Role as RoleEntity;
use App\Service\User as UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class User extends AbstractController
{
    /**
     * @var UserService $userService
     */
    private $userService;


    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->findAll();

        $form = $this->createFormBuilder()
            ->add('filterText', TextType::class, array(
                'label' => 'Valor'))
            ->add('filterType', ChoiceType::class, array(
                'label' => "Campo",
                'choices' => array(
                    'Nome' => 'name',
                    'E-mail' => 'email',
                ),
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Filtrar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $this->handleFilterFormSubmission($form);
        }

        return $this->render('user/index.html.twig', array(
            'users' => $users,
            'filter_form' => $form->createView()
        ));
    }

    private function handleFilterFormSubmission($form)
    {
        $data = $form->getData();
        $users = $this->userService->filter($data);

        return ($users == null) ? array() : $users;
    }

    public function new(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new UserEntity();

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class, ['label' => "Nome"])
            ->add('email', EmailType::class, ['label' => "E-mail"])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Os campos da senha devem corresponder.',
                'options' => array('attr' => ['class' => 'password-field']),
                'required' => true,
                'first_options'  => ['label' => 'Senha'],
                'second_options' => ['label' => 'Repetir senha'],
            ))
            ->add('phone', TelType::class, [
                'label' => 'Telefone',
                'required' => false,
            ])
            ->add('role', EntityType::class, array(
                'label' => 'Cargo do usuário',
                'class' => RoleEntity::class,
                'choice_label' => 'name',
            ))
            ->add('save', SubmitType::class, ['label' => 'Cadastrar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleCreationFormSubmission($form, $encoder);
        }

        return $this->render('user/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function handleCreationFormSubmission($form, $encoder)
    {
        $user = $form->getData();

        $this->userService->create($user, $encoder);

        $this->addFlash(
            'user#success',
            'Cliente cadastrado com sucesso!'
        );

        return $this->redirectToRoute('users');
    }

    public function edit(Request $request, $id)
    {
        $user = $this->userService->findById($id);

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class, ['label' => "Nome"])
            ->add('email', EmailType::class, ['label' => "E-mail"])
            ->add('phone', TelType::class, [
                'label' => 'Telefone',
                'required' => false,
            ])
            ->add('role', EntityType::class, [
                'label' => 'Cargo',
                'class' => RoleEntity::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'Editar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleEditFormSubmission();
        }

        return $this->render('user/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function handleEditFormSubmission()
    {
        $this->userService->update();

        $this->addFlash(
            'user#success',
            'Dados do usuário foram alterados com sucesso!'
        );

        return $this->redirectToRoute('users');
    }

    public function remove(Request $request, $id)
    {
        $this->userService->remove($id);

        $this->addFlash(
            'notice',
            'Usuário removido com sucesso!'
        );

        return $this->redirectToRoute('users');
    }

    public function changePassword(Request $request, $id)
    {
        $user = $this->userService->findById($id);
        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Senhas precisam ser idênticas.',
                'first_options'  => ['label' => 'Nova senha'],
                'second_options' => ['label' => 'Repita a nova senha'],
            ))
            ->add('submit', SubmitType::class, ['label' => 'Atualizar senha'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleChangePasswordSubmission($form, $user);
        }

        return $this->render('profile/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function handleChangePasswordSubmission($form, $user)
    {
        $password = $form->get('password')->getData();
        $this->userService->updatePassword($user, $password);
        $this->addFlash(
            'user#success',
            'Senha do usuário alterada com sucesso!'
        );

        return $this->redirectToRoute('users');
    }
}
