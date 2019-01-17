<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CustomerHistory extends AbstractController
{
    /**
     *
     */
    public function index(Request $request)
    {
        return $this->render('customer_history/index.html.twig');
    }
}
