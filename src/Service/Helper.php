<?php
namespace App\Service;

use App\Entity\Helper as HelperEntity;
use App\Repository\HelperRepository as HelperRepository;
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
    public function checkLastInstallmentActualization()
    {
        $helper = $this->getHelpers();
        $today = new \DateTime(date('Y-m-d'));
        $helperDate = $helper->getLastInstallmentActualization();

        if($today > $helperDate)
        {
            $this->installmentService->updateInstallmentsInArrears($today);

            $helper->setLastInstallmentActualization($today);
            $this->entityManager->persist($helper);
            $this->entityManager->flush();
        }
    }
}
