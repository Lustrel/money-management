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
use Symfony\Component\HttpFoundation\Request;

class User extends AbstractController
{
    public function index(Request $request)
    {
        $users = $this
            ->getDoctrine()
            ->getRepository(UserEntity::class)
            ->findAll();

        return $this->render('users.html.twig', array(
            'users' => $users,
        ));
    }

    public function new(Request $request)
    {
        $user = new UserEntity();

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class, ['label' => "Nome completo"])
            ->add('email', EmailType::class, ['label' => "E-mail"])
            ->add('password', PasswordType::class, ['label' => "Senha"])
            ->add('phone', TelType::class, ['label' => 'Telefone'])
            ->add('role', EntityType::class, ['label' => 'Cargo', 'class' => RoleEntity::class, 'choice_label' => 'name'])
            ->add('save', SubmitType::class, array('label' => 'Cadastrar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('users');
        }

        return $this->render('create-user.html.twig', array(
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
            ->add('role', EntityType::class, ['label' => 'Cargo', 'class' => RoleEntity::class, 'choice_label' => 'name'])
            ->add('save', SubmitType::class, array('label' => 'Cadastrar'))
            ->getForm();

        $form->handleRequest($request);

        return $this->render('edit-user.html.twig', array(
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

        return $this->redirectToRoute('users');
    }
}