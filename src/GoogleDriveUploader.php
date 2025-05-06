<?php

namespace App;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDriveUploader
{
    private $client;
    private $driveService;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig('C:\Users\LENOVO I5\Downloads\uservf - Copie\uservf - Copie\credentials\nextgenhr-contrats-73503343773f.json'); // chemin du fichier JSON
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
