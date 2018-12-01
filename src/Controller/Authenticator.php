<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Loan;
use App\Entity\Login;
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


class Authenticator extends AbstractController
{
    public function new(Request $request)
    {
        $session = new Session();
        if ($request->hasSession() && ($session = $request->getSession())) {
            if ($session->get('logged') == true) {
                return $this->redirectToRoute('reports');
            }
        }


        // creates a auth and gives it some dummy data for this example
        $auth = new Login();


        $form = $this->createFormBuilder($auth)
            ->add('username',        TextType::class,    ['label' => "Usuário: "])
            ->add('password',        TextType::class,    ['label' => "Senha: "])
            ->add('save',           SubmitType::class, array('label' => 'Entrar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$auth` variable has also been updated
            $auth = $form->getData();

            //var_dump($auth->getUsername(),$auth->getPassword()); die;
            $login = $this->getDoctrine()
                ->getRepository(User::class)
                ->findBy(array('email' => $auth->getUsername(), 'password' =>$auth->getPassword()));

            if ($login == null) {
                echo "login ou senha errados";
            }

            else {

                $user   =   new User();
                $user   = $login[0];
                //var_dump($user->getName()); die;



               // $session->start();

                // set and get session attributes
                $session->set('user_id', $user->getId());
                $session->set('name', $user->getName());
                $session->set('job', $user->getJob());
                $session->set('logged', true);

                return $this->redirectToRoute('register_user');


            }
        }

        return $this->render('login.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}