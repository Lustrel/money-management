<?php
namespace App\Service;

date_default_timezone_set('America/Sao_paulo');

use App\Entity\Helper as HelperEntity;
use App\Repository\HelperRepository as HelperRepository;
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
     * Construct.
     */
    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->helperRepository = $entityManager->getRepository(HelperEntity::class);
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

            dump("today maior que helperdate");
        }
        dump($helper, $today, $helperDate);exit;
    }
}
