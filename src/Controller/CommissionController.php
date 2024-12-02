<?php
namespace App\Controller;

use App\Entity\Commission;
use App\Entity\Usercommission;
use App\Repository\CommissionRepository;
use App\Form\CommissionType;
use App\Form\CommissionEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $this->addFlash('success', 'Le groupe a été créé avec succès !'); 
            return $this->redirectToRoute('commission_success');
        }
        return $this->render('commission/new.html.twig', [
            'commission' => $commission,
            'form' => $form->createView(),
        ]);
    }

    public function chat(CommissionRepository $commissionRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette commission.');
            return $this->redirectToRoute('app_login');
        }
    
        $commission = $commissionRepository->find($id);
        if (!$commission) {
            throw $this->createNotFoundException('Commission introuvable');
        }
    
        // Récupérer ou créer l'entrée Usercommission
        $userCommissionRepository = $entityManager->getRepository(Usercommission::class);
        $userCommission = $userCommissionRepository->findOneBy([
            'user' => $user,
            'commission' => $commission,
        ]);
    
        if ($userCommission) {
            // Mettre à jour la dernière visite
            $userCommission->setLastVisit(new \DateTime());
        } else {
            // Créer une nouvelle relation Usercommission si elle n'existe pas
            $userCommission = new Usercommission();
            $userCommission->setUser($user);
            $userCommission->setCommission($commission);
            $userCommission->setIsFollowed(false); // Défaut : non abonné
            $userCommission->setLastVisit(new \DateTime());
    
            $entityManager->persist($userCommission);
        }
    
        $entityManager->flush();
    
        return $this->render('commission/chat.html.twig', [
            'commission' => $commission,
            'currentCommissionId' => $id,
        ]);
    }
    
    

    public function list(CommissionRepository $groupRepository): Response
    {
        $group = $groupRepository->findAll();

        return $this->render('commission/list.html.twig', [
            'groups' => $group,
        ]);
    }

    public function edit(Request $request, Commission $commission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommissionEditType::class, $commission);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister les modifications
            $entityManager->persist($commission);
            $entityManager->flush();
            
            return $this->redirectToRoute('commission_list');
        }
    
        return $this->render('commission/edit.html.twig', [
            'commissionForm' => $form->createView(),
        ]);
    }

    public function toggleSubscription(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Vous devez être connecté pour modifier votre abonnement.'], 403);
        }

        $commission = $entityManager->getRepository(Commission::class)->find($id);
        if (!$commission) {
            return new JsonResponse(['error' => 'Commission introuvable.'], 404);
        }

        $userCommissionRepository = $entityManager->getRepository(Usercommission::class);
        $userCommission = $userCommissionRepository->findOneBy([
            'user' => $user,
            'commission' => $commission,
        ]);

        $isFollowed = false;

        if ($userCommission) {
            // Basculer l'état d'abonnement
            $isFollowed = !$userCommission->isFollowed();
            $userCommission->setIsFollowed($isFollowed);
        } else {
            // Créer une nouvelle entrée avec un abonnement actif
            $userCommission = new Usercommission();
            $userCommission->setUser($user);
            $userCommission->setCommission($commission);
            $userCommission->setIsFollowed(true);
            $isFollowed = true;
            $entityManager->persist($userCommission);
        }

        $entityManager->flush();

        return new JsonResponse(['isFollowed' => $isFollowed]);
    }

    public function success(): Response
    {
        return $this->render('commission/success.html.twig');
    }
}