<?php
namespace App\Controller;

use App\Entity\Installment as InstallmentEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Entity\User as UserEntity;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Installment extends Controller
{
    public function index(Request $request)
    {    
        $installments = $this
            ->getDoctrine()
            ->getRepository(InstallmentEntity::class)
            ->findAll();

        return $this->render('installment/installments.html.twig', array(
            'installments' => $installments,
        ));
    }

    public function pay(Request $request, $id)
    {
        $entityManager = $this
            ->getDoctrine()
            ->getManager();

        $installmentStatusRepository = $this
            ->getDoctrine()
            ->getRepository(InstallmentStatusEntity::class);

        $installmentRepository = $this
            ->getDoctrine()
            ->getRepository(InstallmentEntity::class);

        /** @var InstallmentEntity $installment */
        $installment = $installmentRepository->find($id);

        $updatedInstallment = (new InstallmentEntity())
            ->setValue($installment->getValue());

        $form = $this->createFormBuilder($updatedInstallment)
            ->add('value', NumberType::class, ['label' => "Valor pago"])
            ->add('save', SubmitType::class, ['label' => 'Dar baixa'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $paidStatus = $installmentStatusRepository->find(3);

            $value = $installment->getValue();
            $updatedValue = $updatedInstallment->getValue();

            if ($updatedValue < $value)
            {
                $installment->setValue($updatedValue);
                $nextInstallment = $installmentRepository->find($installment->getId() + 1);
                if ($nextInstallment)
                {
                    $nextInstallment->setValue(
                        $nextInstallment->getValue() + (($value - $updatedValue) * 1.05)
                    );

                    $entityManager->persist($nextInstallment);
                }
            }

            $installment->setStatus($paidStatus);
            $entityManager->persist($installment);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Baixa de parcela efetuada com sucesso!'
            );

            return $this->redirectToRoute('installments');
        }

        return $this->render('installment/pay.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}