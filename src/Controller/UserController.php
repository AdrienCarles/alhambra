<?php
namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function list(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }   

    public function detail(UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);

        $currentUserId = $this->getUser()->getId();

        if (!$user) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }

        return $this->render('user/detail.html.twig', [
            'user' => $user,
            'currentUserId' => $currentUserId,
        ]);
    }

    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if (!empty($plainPassword)) {
                // Encodez et définissez le nouveau mot de passe
                $encodedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($encodedPassword);
    
                // Persistez les modifications
                $entityManager->persist($user);
                $entityManager->flush();
    
                return $this->redirectToRoute('user_list');
            }
        }
    
        return $this->render('user/edit.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }
}