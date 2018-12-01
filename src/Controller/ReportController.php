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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller
{
    public function new(Request $request)
    {

        $session = new Session();


        /*//var_dump($session->get('name')); die;*/

           //$result = $this->showUsers(0);
           //var_dump($result); die;
           //var_dump($result); die;
            //return $result;

         // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $userRepository = $em->getRepository(User::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allUserQuery = $userRepository->createQueryBuilder('users')
            ->getQuery()
            ->execute();


        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');



        // Paginate the results of the query
        $users = $paginator->paginate(
        // Doctrine Query, not results
            $allUserQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );

      ;

        // Render the twig view
        return $this->render('list_users.html.twig', [
            'pagination' => $users
        ]);





    }



    public function showUsers($id)
    {
        if ($id== null) {
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll();
            return $this->render('list_users.html.twig', ['results' => $users]);
        }
        else {
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($id);
            return $this->render('user.html.twig', ['results' => $users]);
        }

        if (!$users) {
            throw $this->createNotFoundException(
                'No User found for id '.$id
            );
        }

        return $users;
        //var_dump($users->getName()); die;
        //return $this->render('list_users.html.twig', ['results' => $users]);

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }
}