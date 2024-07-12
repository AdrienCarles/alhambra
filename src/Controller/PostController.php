<?php
// src/Controller/PostController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\Post;
use App\Entity\Commission;
use Doctrine\ORM\EntityManagerInterface;

class PostController extends AbstractController
{
    public function create(Request $request, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response
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

        // Associer le post à la commission
        $commissionId = $request->request->get('commission_id');
        $commission = $entityManager->getRepository(Commission::class)->find($commissionId);
        if (!$commission) {
            throw $this->createNotFoundException('Commission not found.');
        }
        $post->setCommission($commission);

        // Enregistrer le post
        $entityManager->persist($post);
        $entityManager->flush();

        // Rediriger vers la commission
        return $this->redirectToRoute('commission_chat', ['id' => $commissionId]);
    }
}