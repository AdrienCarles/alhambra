<?php
// src/Controller/NotificationController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UsercommissionRepository;
use App\Repository\CommissionRepository;

class NotificationController extends AbstractController
{
    #[Route('/commission/notifications', name: 'commission_notifications', methods: ['GET'])]
    public function getNotifications(
        EntityManagerInterface $entityManager,
        UsercommissionRepository $usercommissionRepository,
        CommissionRepository $commissionRepository
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], 403);
        }

        $commissions = $commissionRepository->findAll();
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
