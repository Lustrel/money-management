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

class Profile extends AbstractController
{
    /**
     * @var UserService $userService
     */
    private $userService;

    public function __construct(
        UserService $userService
    )
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Senhas precisam ser idênticas.',
                'options' => array('attr' => ['class' => 'password-field']),
                'required' => true,
                'first_options'  => ['label' => 'Nova senha'],
                'second_options' => ['label' => 'Repetir nova senha'],
            ))
            ->add('submit', SubmitType::class, ['label' => 'Atualizar perfil'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleEditFormSubmission($form);
        }

        return $this->render('profile/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function handleEditFormSubmission($form)
    {
        $data = $form->getData();

        $this->userService->updatePassword($this->getUser(), $data['password']);

        $this->addFlash(
            'profile#updated_successfully',
            'Perfil atualizado com sucesso'
        );

        return $this->redirectToRoute('profile');
    }
}
