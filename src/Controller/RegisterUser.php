<?php
namespace App\Controller;

use App\Entity\Loan;
use App\Entity\User;
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

        $session = new Session();
        /*//var_dump($session->get('name')); die;
        if ($session->get('job') < 7777) {
            return $this->render('access_not_allowed.html.twig'
            );
        }*/

        // creates a task and gives it some dummy data for this example
        $user = new User();
        //$user->setTask('Write a blog post');
        $user->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class, ['label' => "Nome: "])
            ->add('document', NumberType::class, ['label' => "CPF / CNPJ: "])
            ->add('address', TextType::class, ['label' => "Endereço: "])
            ->add('phone', TelType::class, ['label' => "Telefone: "])
            ->add('email', EmailType::class, ['label' => "Email: "])
            ->add('password', TextType::class, ['label' => "Senha: "])
            ->add('seller', NumberType::class, ['label' => "Responsável: "])
            ->add('job', ChoiceType::class, array(
                'label'   =>    "Cargo: ",
                'choices' => array(
                    'Opções' => array(
                        'Administrador' => 9999,
                        'Gerente'       => 8888,
                        'Vendedor'      => 7777,
                        'Cliente'       => 6666,
                    ),
                )
            ))
            //->add('dueDate', DateType::class)
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