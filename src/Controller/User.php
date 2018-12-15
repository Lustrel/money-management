<?php
namespace App\Controller;

use App\Entity\User as UserEntity;
use App\Entity\Role as RoleEntity;
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
    public function index(Request $request)
    {
        $users = $this
            ->getDoctrine()
            ->getRepository(UserEntity::class)
            ->findAll();

        $form = $this->createFormBuilder()
        ->add('filterText', TextType::class, ['label' => 'Filtrar por'])
        ->add('filterType', ChoiceType::class, array(
            'label' => "Campo",
            'choices' => array(
                'Nome' => 'name',
                'E-mail' => 'email'
            ),
        ))
        ->add('filter', SubmitType::class, ['label' => 'Filtrar'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $data = $form->getData();
 
            $filterUsers = $this
            ->getDoctrine()
            ->getRepository(UserEntity::class)
            ->findBy(array($data['filterType'] => $data['filterText']));
            if($filterUsers == null){
                $this->addFlash(
                    'notice',
                    'Não há registros com esses dados!'
                );
            }else{
                $users = $filterUsers;
            }
        }

        return $this->render('user/users.html.twig', array(
            'users' => $users, 'form' => $form->createView()
        ));
    }

    public function new(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new UserEntity();

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class, ['label' => "Nome do usuário"])
            ->add('email', EmailType::class, ['label' => "E-mail"])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Os campos da senha devem corresponder.',
                'options' => array('attr' => ['class' => 'password-field']),
                'required' => true,
                'first_options'  => ['label' => 'Senha'],
                'second_options' => ['label' => 'Repetir senha'],
            ))
            ->add('phone', TelType::class, ['label' => 'Telefone'])
            ->add('role', EntityType::class, array(
                'label' => 'Cargo do usuário',
                'class' => RoleEntity::class,
                'choice_label' => 'name'))
            ->add('save', SubmitType::class, ['label' => 'Cadastrar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Usuário cadastrado com sucesso!'
            );

            return $this->redirectToRoute('users');
        }

        return $this->render('user/create-user.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function edit(Request $request, $id)
    {
        $user = $this
            ->getDoctrine()
            ->getRepository(UserEntity::class)
            ->findOneBy(array('id' => $id));

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class, ['label' => "Nome completo"])
            ->add('email', EmailType::class, ['label' => "E-mail"])
            ->add('phone', TelType::class, ['label' => 'Telefone'])
            ->add('role', EntityType::class, [
                'label' => 'Cargo',
                'class' => RoleEntity::class,
                'choice_label' => 'name'
            ])
            ->add('save', SubmitType::class, ['label' => 'Editar'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Dados do usuário foram alterados com sucesso!'
            );

            return $this->redirectToRoute('users');
        }

        return $this->render('user/edit-user.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function remove(Request $request, $id)
    {
        $user = $this
            ->getDoctrine()
            ->getRepository(UserEntity::class)
            ->findOneBy(array('id' => $id));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Usuário removido com sucesso!'
        );

        return $this->redirectToRoute('users');
    }
}