<?php
namespace App\Controller;

use App\Service\Profit as ProfitService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Profit extends Controller
{
    /**
     * @var ProfitService $profitService
     */
    private $profitService;


    public function __construct(ProfitService $profitService)
    {
        $this->profitService = $profitService;
    }

    public function index(Request $request)
    {
        $profits = $this->profitService->findAll();
        return $this->render('profit/index.html.twig', array(
            'profits' => $profits,
        ));
    }
}
