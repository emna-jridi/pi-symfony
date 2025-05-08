<?php

namespace App\Controller;

use App\Service\ChatbotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChatbotController extends AbstractController
{
    #[Route('/chatbot/message', name: 'app_chatbot_message', methods: ['POST'])]
    public function getResponse(Request $request, ChatbotService $chatbotService): JsonResponse
    {
        $message = $request->request->get('message');
        
        if (empty($message)) {
            return new JsonResponse(['error' => 'Message vide'], 400);
        }

        $response = $chatbotService->getResponse($message);

        return new JsonResponse(['response' => $response]);
    }
} 