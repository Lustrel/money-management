<?php
namespace App\Service;

date_default_timezone_set('America/Sao_paulo');

use App\Entity\Helper as HelperEntity;
use App\Entity\InstallmentStatus as InstallmentStatusEntity;
use App\Repository\HelperRepository as HelperRepository;
use App\Repository\InstallmentStatusRepository;
use App\Service\Installment as InstallmentService;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class Helper
{
    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var HelperRepository $helperRepository
     */
    private $helperRepository;

    /**
     * @var InstallmentStatusRepository $installmentStatusRepository
     */
    private $installmentStatusRepository;

    /**
     * @var InstallmentService $installmentService
     */
    private $installmentService;

    /**
     * Construct.
     */
    public function __construct(
        EntityManager $entityManager,
        InstallmentService $installmentService
    )
    {
        $this->entityManager = $entityManager;
        $this->helperRepository = $entityManager->getRepository(HelperEntity::class);
        $this->installmentStatusRepository = $entityManager->getRepository(InstallmentStatusEntity::class);
        $this->installmentService = $installmentService;
    }

    /**
     * Get helpers
     */
    public function getHelpers()
    {
        $helper = $this->helperRepository->findAll();
        return $helper[0];
    }

    /**
     * 
     */
    public function lastInstallmentActualization()
    {
        $helper = $this->getHelpers();
        $today = new \DateTime(date('Y-m-d'));
        $helperDate = $helper->getLastInstallmentActualization();

        if($today > $helperDate)
        {
            $toReceiveInstallments = $this->installmentService->findByStatus(
                $this->installmentStatusRepository->getToReceive()
            );

            foreach ($toReceiveInstallments as $installment) {
               $installmentDueDate = $installment->getDueDate();
               if($today > $installmentDueDate)
               {
                   $installment->setStatus(
                       $this->installmentStatusRepository->getInArrears());
                    $this->entityManager->persist($installment);
                    $this->entityManager->flush();
               }
            }

            $helper->setLastInstallmentActualization($today);
            $this->entityManager->persist($helper);
            $this->entityManager->flush();
        }
    }
}
