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
        $temporaryCommissions = [];
        $globalCommissions = [];
    
        foreach ($commissions as $commission) {
            if ($commission->getIsGeneral()) {
                $globalCommissions[] = $commission;
            } else {
                $temporaryCommissions[] = $commission;
            }
        }

        return $this->render('home/index.html.twig', [
            'temporaryCommissions' => $temporaryCommissions,
            'globalCommissions' => $globalCommissions,
        ]);
    }
}
