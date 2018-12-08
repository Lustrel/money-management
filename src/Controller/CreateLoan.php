<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Loan;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


class CreateLoan extends AbstractController
{
    public function new(Request $request)
    {

        $session = new Session();

        if ($session->get('job') < 7777) {
            return $this->render('access_not_allowed.html.twig'
            );
        }
        // creates a task and gives it some dummy data for this example
        $task = new Loan();
        //$task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));
        //$task->setUser(new \App\Entity\User());

        $form = $this->createFormBuilder($task)

           /*->add('user',           EntityType::class,  array(
                'class' => 'App:User',
                'choice_label' => 'name',
                'label'        => "Nome: "
                ))*/
            ->add('user', AutocompleteType::class, ['class' => User::class, 'label' => 'Nome:'])
            ->add('loanValue', NumberType::class, ['label' => "Valor: "])
            ->add('installments', NumberType::class, ['label' => "Parcelas: "])
            ->add('fee', NumberType::class, ['label' => "Taxa: ", 'data' => "20"])
            ->add('discount', NumberType::class, ['label' => "Desconto: "])
            ->add('info', TextType::class, ['label' => "Observações: "])
            ->add('dueDate', DateType::class, ['label' => "Data de Vencimento: "])
            ->add('save', SubmitType::class, array('label' => 'Cadastrar Empréstimo'))
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            //$user = new User();
            parse_str($request->getContent(), $get_array);
           // $task->setUser($get_array["userId"]);

            $userTakingLoan = $this->getUser($get_array['userId']);
            $task->setUser($userTakingLoan);

           // parse_str($request->getContent(), $get_array);
            //var_dump($get_array); die;


            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->render('user-registered-successfully.twig'
            );
        }

        return $this->render('createLoan.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /*
      public function searchUser(Request $request)
      {
          //$user = new User();
          $q = $request->query->get('q'); // use "term" instead of "q" for jquery-ui

         // var_dump($q); die;

          $result = $this->getDoctrine()
              ->getRepository(User::class)
              ->findOneBy(array('name' => $q));
          //$user = $user->getDoctrine()->getRepository('App:User')->findOneBy(array('name' => $q),array('name' => 'DESC'));
          var_dump($result); die;



          return $this->render('your_template.json.twig', ['results' => $result]);
      }*/

    public function searchUser(Request $request)
    {
        $user = new User();
        $q = $request->query->get('q'); // use "term" instead of "q" for jquery-ui
        $results = $this->getDoctrine()->getRepository('App:User')->findLikeName($q);


        return $this->render('your_template.json.twig', ['results' => $results]);
    }

    public function getUser($id = null)
    {
        $author = $this->getDoctrine()->getRepository('App:User')->find($id);

        return $author;
    }
}