<?php

namespace App\Controller;

use App\Entity\Usercommission;
use App\Repository\UserRepository;
use App\Repository\CommissionRepository;
use App\Form\UserCommissionType;
use App\Repository\UsercommissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsercommissionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function follow(Request $request,CommissionRepository $groupRepository, $idCommission): Response
    {
        $commission = $groupRepository->find($idCommission);
        $Usercommission = new Usercommission();
        $form = $this->createForm(UserCommissionType::class, $Usercommission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($Usercommission);
            $this->entityManager->flush();
            $this->addFlash('success', 'Groupe suivi'); 
            return $this->redirectToRoute('commission/chat.html.twig');
        }
        return $this->render('commission/chat.html.twig', [
            'commission' => $commission,
            'currentCommissionId' => $id,
        ]);
    }
}