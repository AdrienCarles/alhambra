<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CommissionRepository;

class HomeController extends AbstractController
{
    public function index(CommissionRepository $commissionRepository): Response
    {
        $commissions = $commissionRepository->findAll();

        return $this->render('home/index.html.twig', [
            'commissions' => $commissions,
        ]);
    }
}
