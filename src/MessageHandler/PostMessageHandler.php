<?php
namespace App\MessageHandler;

use App\Message\PostMessage;
use App\Entity\Notification;
use App\Repository\UserRepository;
use App\Repository\CommissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PostMessageHandler implements MessageHandlerInterface
{
    private $entityManager;
    private $userRepository;
    private $commissionRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, CommissionRepository $commissionRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->commissionRepository = $commissionRepository;
    }

    public function __invoke(PostMessage $message)
    {
        $user = $this->userRepository->find($message->getUserId());
        $commission = $this->commissionRepository->find($message->getCommissionId());

        if (!$user || !$commission) {
            return;
        }

        // Créer une notification ou effectuer une autre action
        $notification = new Notification();
        $notification->setMessage($message->getContent());
        $notification->setUser($user);
        $notification->setDate(new \DateTime());

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}