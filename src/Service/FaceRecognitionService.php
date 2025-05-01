<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FaceRecognitionService
{
    private $apiKey;
    private $apiSecret;
    private $baseUrl = 'https://api-us.faceplusplus.com/facepp/v3/detect';

    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function detectFace(UploadedFile $image): array
    {
        $client = HttpClient::create();
        $response = $client->request('POST', $this->baseUrl, [
            'body' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'image_file' => fopen($image->getPathname(), 'r'),
                'return_landmark' => 0,
                'return_attributes' => '',
            ],
        ]);
        return $response->toArray();
    }

    public function compareFaces(UploadedFile $image1, UploadedFile $image2): array
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'https://api-us.faceplusplus.com/facepp/v3/compare', [
            'body' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'image_file1' => fopen($image1->getPathname(), 'r'),
                'image_file2' => fopen($image2->getPathname(), 'r'),
            ],
        ]);
        return $response->toArray();
    }

    public function verifyFace(UploadedFile $image, string $faceId): array
    {
        $client = HttpClient::create();
        
        // Convertir l'image en base64
        $imageData = base64_encode(file_get_contents($image->getPathname()));
        
        $response = $client->request('POST', $this->baseUrl . '/verify', [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-API-KEY' => $this->apiKey,
                'X-API-SECRET' => $this->apiSecret,
            ],
            'json' => [
                'image' => $imageData,
                'face_id' => $faceId,
            ],
        ]);

        return $response->toArray();
    }
} 