<?php
namespace App\Controller;

use App\Entity\Loan;
use App\Entity\User;
use App\Entity\Role as RoleEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Session\Session;

class RegisterUser extends AbstractController
{
    public function new(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, ['label' => "Email: "])
            ->add('password', TextType::class, ['label' => "Senha: "])
            ->add('phone', TelType::class, ['label' => "Telefone: "])
            ->add('role', EntityType::class, array(
                'label' => 'Cargo:',
                'class' => RoleEntity::class,
                'choice_label' => 'name'
            ))
            ->add('save', SubmitType::class, array('label' => 'Cadastrar Usuário'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$user` variable has also been updated
            $user = $form->getData();

            //var_dump($user); die;

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();



            //return $this->redirectToRoute('register_success');
            return $this->render('registerUserSuccess.html.twig'
            );
        }

        return $this->render('registerUser.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}