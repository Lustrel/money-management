<?php
namespace App\Controller;

use App\Entity\User as UserEntity;
use App\Service\User as UserService;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
     * Construct.
     */
    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    /**
     *
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Senhas precisam ser idênticas.',
                'options' => array('attr' => ['class' => 'password-field']),
                'required' => true,
                'first_options'  => ['label' => 'Senha'],
                'second_options' => ['label' => 'Repetir senha'],
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
        
        $this->userService->updatePassword($this->getUser(), $data['password'], $encoder);

        $this->addFlash(
            'success',
            'Perfil atualizado com sucesso'  
        );

        return $this->redirectToRoute('profile');
    }
}
