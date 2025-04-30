<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{
    private $mailer;
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function generateResetCode(): string
    {
        // Génère un code à 6 chiffres
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function sendResetPasswordEmail(string $to, string $resetCode): void
    {
        try {
            $email = (new Email())
                ->from('noreply@nextgenrh.com')
                ->to($to)
                ->subject('Réinitialisation de votre mot de passe')
                ->html($this->twig->render('email/reset_password.html.twig', [
                    'resetCode' => $resetCode
                ]));

            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Log l'erreur si nécessaire
            throw $e;
        }
    }
} 