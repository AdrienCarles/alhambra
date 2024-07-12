<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChatController extends AbstractController
{
    public function conversation(): Response
    {
        // Replace with real data fetching logic
        $conversations = [
            ['name' => 'Alice', 'lastMessage' => 'Hey, how are you?', 'time' => '12:45 PM'],
            ['name' => 'Bob', 'lastMessage' => 'See you tomorrow!', 'time' => '11:30 AM']
        ];

        return $this->render('chat/index.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    public function chat(int $id): Response
    {
        // Replace with real data fetching logic
        $messages = [
            ['text' => 'Hello!', 'sender' => true],
            ['text' => 'Hi, how are you?', 'sender' => false]
        ];

        return $this->render('chat/chat.html.twig', [
            'messages' => $messages,
        ]);
    }
}