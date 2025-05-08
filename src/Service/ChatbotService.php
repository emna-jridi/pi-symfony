<?php

namespace App\Service;

class ChatbotService
{
    private array $responses;

    public function __construct()
    {
        $this->responses = json_decode(file_get_contents(__DIR__ . '/../../config/chatbot_responses.json'), true);
    }

    public function getResponse(string $message): string
    {
        $message = strtolower(trim($message));

        foreach ($this->responses as $category => $data) {
            if ($category === 'default') {
                continue;
            }

            foreach ($data['patterns'] as $pattern) {
                if (strpos($message, $pattern) !== false) {
                    return $this->getRandomResponse($data['responses']);
                }
            }
        }

        return $this->getRandomResponse($this->responses['default']['responses']);
    }

    private function getRandomResponse(array $responses): string
    {
        return $responses[array_rand($responses)];
    }
} 