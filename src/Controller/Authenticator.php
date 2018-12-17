<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Authenticator extends AbstractController
{
    public function login(Request $request, AuthenticationUtils $util)
    {
       $errors = $util->getLastAuthenticationError();
       $lastUsername = $util->getLastUsername();

        return $this->render('login/login.html.twig', array(
            'errors' => $errors,
            'username' => $lastUsername, 
        ));
    }

    public function logout(){}
}