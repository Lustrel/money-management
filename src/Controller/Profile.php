<?php
namespace App\Controller;

use App\Entity\User as UserEntity;
use App\Repository\UserRepository as UserRepository;
use App\Service\User as UserService;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class Profile extends AbstractController
{
    /**
     * @var UserService $userService
     */
    private $userService;

    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * Construct.
     */
    public function __construct(
        UserService $userService,
        UserRepository $userRepository
    )
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    /**
     *
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        //$loans = $this->loanService->findAll();

        $form = $this->createFormBuilder()
            ->add('password', PasswordType::class, array(
                'label' => 'Nova senha',
            ))
            ->add('repeatPassword', PasswordType::class, array(
                'label' => "Repetir nova senha",
            ))
            ->add('submit', SubmitType::class, ['label' => 'Atualizar perfil'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleEditFormSubmission($form, $encoder);
        }

        return $this->render('profile/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * 
     */
    public function handleEditFormSubmission($form, $encoder)
    {
        $data = $form->getData();

        if(strcmp($data['password'], $data['repeatPassword']) != 0)
        {
            $this->addFlash(
                'danger',
                'Senhas fornecidas não conferem'  
            );
            
            return $this->redirectToRoute('profile');
        }

        $this->userService->update($this->getUser(), $data['password'], $encoder);
        $this->addFlash(
            'success',
            'Perfil atualizado com sucesso'  
        );
    }
}
