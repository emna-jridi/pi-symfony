<?php

// src/Service/SMSNotificationService.php

namespace App;

use Twilio\Rest\Client;

class SMSNotification
{
    private $sid;
    private $authToken;
    private $twilioNumber;

    public function __construct(string $sid, string $authToken, string $twilioNumber)
    {
        $this->sid = $sid;
        $this->authToken = $authToken;
        $this->twilioNumber = $twilioNumber;
    }

    public function sendSMS(string $to, string $message): void
    {
        // Initialisation de Twilio Client
        $client = new Client($this->sid, $this->authToken);

        // Envoi du SMS
        $client->messages->create(
            $to, // Le numéro de téléphone du destinataire
            [
                'from' => $this->twilioNumber, // Votre numéro Twilio
                'body' => $message, // Le contenu du SMS
            ]
        );
    }
}

