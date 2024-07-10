<?php
namespace App\Controller;

use App\Entity\Commission;
use App\Repository\CommissionRepository;
use App\Form\CommissionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommissionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function new(Request $request): Response
    {
        $commission = new Commission();
        $form = $this->createForm(CommissionType::class, $commission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($commission);
            $this->entityManager->flush();
            return $this->redirectToRoute('commission_success');
        }
        return $this->render('commission/new.html.twig', [
            'commission' => $commission,
            'form' => $form->createView(),
        ]);
    }

    public function list(CommissionRepository $groupRepository): Response
    {
        $group = $groupRepository->findAll();

        return $this->render('commission/list.html.twig', [
            'groups' => $group,
        ]);
    }


    public function success(): Response
    {
        return $this->render('commission/success.html.twig');
    }
}