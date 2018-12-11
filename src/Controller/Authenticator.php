<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;


class Authenticator extends AbstractController
{
    public function new(Request $request)
    {
        $session = new Session();
        if ($request->hasSession() && ($session = $request->getSession())) {
            if ($session->get('logged') == true) {
                return $this->redirectToRoute('users');
            }
        }


        // creates a user and gives it some dummy data for this example
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('email', TextType::class,    ['label' => "E-mail: "])
            ->add('password', PasswordType::class,    ['label' => "Senha: "])
            ->add('save', SubmitType::class, array('label' => 'Entrar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $loggedUser = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(array('email' => $user->getEmail(), 'password' =>$user->getPassword()));

            if ($loggedUser == null) {
                echo "Login ou senha incorretos";
            }

            else {
                /** @var \App\Entity\User $user */
                $user = $loggedUser[0];

                $session->set('user_id', $user->getId());
                $session->set('logged', true);

                return $this->redirectToRoute('users');
            }
        }

        return $this->render('login.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}