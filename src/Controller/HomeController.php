<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\CommissionRepository;
use App\Repository\UsercommissionRepository;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    public function index(CommissionRepository $commissionRepository, UsercommissionRepository $usercommissionRepository, EntityManagerInterface $entityManager): Response
    {
        $commissions = $commissionRepository->findAll();
        $user = $this->getUser(); 
        $subscriptions = [];
        $unreadMessages = [];
    
        if ($user) {
            foreach ($commissions as $commission) {
                $subscription = $usercommissionRepository->findOneBy([
                    'user' => $user,
                    'commission' => $commission,
                ]);
    
                $isFollowed = $subscription ? $subscription->isFollowed() : false;
                $subscriptions[$commission->getId()] = $isFollowed;
    
                if ($isFollowed) {
                    $lastVisit = $subscription->getLastVisit();
                    $queryBuilder = $entityManager->createQueryBuilder();
    
                    $unreadCount = $queryBuilder
                        ->select('COUNT(p.id)')
                        ->from('App\Entity\Post', 'p')
                        ->where('p.commission = :commission')
                        ->andWhere('p.createdAt > :lastVisit')
                        ->setParameter('commission', $commission)
                        ->setParameter('lastVisit', $lastVisit ?: new \DateTime('1970-01-01'))
                        ->getQuery()
                        ->getSingleScalarResult();
    
                    $unreadMessages[$commission->getId()] = $unreadCount;
                }
            }
        }
    
        return $this->render('home/index.html.twig', [
            'commissions' => $commissions,
            'subscriptions' => $subscriptions,
            'unreadMessages' => $unreadMessages, 
        ]);
    }

    public function fetchNotifications(
        CommissionRepository $commissionRepository,
        UsercommissionRepository $usercommissionRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $commissions = $commissionRepository->findAll();
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 403);
        }

        $notifications = [];

        foreach ($commissions as $commission) {
            $subscription = $usercommissionRepository->findOneBy([
                'user' => $user,
                'commission' => $commission,
            ]);

            if ($subscription && $subscription->isFollowed()) {
                $lastVisit = $subscription->getLastVisit() ?: new \DateTime('1970-01-01');
                $queryBuilder = $entityManager->createQueryBuilder();

                $unreadCount = $queryBuilder
                    ->select('COUNT(p.id)')
                    ->from('App\Entity\Post', 'p')
                    ->where('p.commission = :commission')
                    ->andWhere('p.createdAt > :lastVisit')
                    ->setParameter('commission', $commission)
                    ->setParameter('lastVisit', $lastVisit)
                    ->getQuery()
                    ->getSingleScalarResult();

                $notifications[$commission->getId()] = $unreadCount;
            }
        }

        return new JsonResponse(['notifications' => $notifications]);
    }
}
