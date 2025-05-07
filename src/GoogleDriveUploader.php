<?php

namespace App;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleDriveUploader
{
    private $client;
    private $driveService;
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        
        $this->client = new Client();
        $this->client->setAuthConfig($this->parameterBag->get('kernel.project_dir') . '/' . $_ENV['GOOGLE_APPLICATION_CREDENTIALS']);
        $this->client->addScope(Drive::DRIVE_FILE);

        $this->driveService = new Drive($this->client);
    }

    public function upload(string $filePath, string $fileName, string $folderId): string
    {
        $file = new DriveFile();
        $file->setName($fileName);
        $file->setParents([$folderId]);

        $content = file_get_contents($filePath);

        $uploadedFile = $this->driveService->files->create($file, [
            'data' => $content,
            'mimeType' => 'application/pdf',
            'uploadType' => 'multipart',
        ]);

        return $uploadedFile->id;
    }
}