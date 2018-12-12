<?php
namespace App\Controller;

use App\Entity\Installment as InstallmentEntity;
use App\Entity\User as UserEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Installment extends Controller
{
    public function index(Request $request)
    {
        $session = $request->getSession();
        if (!$session || !$session->get('logged_user_id')) {
            return $this->redirect('/');
        }

        $doctrine = $this->getDoctrine();

        $currentUser = $doctrine
            ->getRepository(UserEntity::class)
            ->find($session->get('logged_user_id'));

        $installments = $doctrine
            ->getRepository(InstallmentEntity::class)
            ->findBySellerUser($currentUser);

        return $this->render('installment/index.html.twig', array(
            'installments' => $installments,
        ));
    }
}