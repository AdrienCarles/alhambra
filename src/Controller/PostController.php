<?php
namespace App\Controller;

use App\Message\PostMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class PostController extends AbstractController
{
    public function create(Request $request, MessageBusInterface $bus): Response
    {
        // Vérifier l'authentification de l'utilisateur
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('User not found.');
        }

        // Créer le post
        $post = new Post();
        $post->setContent($request->request->get('post_content'));
        $post->setUser($user);
        $post->setCreatedAt(new \DateTime());

        // Associer le post à la commission
        $commissionId = $request->request->get('commission_id');
        $message = new PostMessage($content, $user->getId(), $commissionId);

        // Envoyer le message
        $bus->dispatch($message);

        // Rediriger vers la commission
        return $this->redirectToRoute('commission_chat', ['id' => $commissionId]);
    }
}