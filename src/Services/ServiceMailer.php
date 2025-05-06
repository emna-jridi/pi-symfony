<?php

namespace App\Services;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;


class ServiceMailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendAcceptationEmail(string $recipientEmail, string $candidateName)
    {
        $email = (new Email())
            ->from('myriambenabdallah17@gmail.com')
            ->to($recipientEmail)
            ->subject('Félicitations ! Votre candidature a été acceptée')
            ->text("Bonjour $candidateName,\n\nNous sommes heureux de vous informer que votre candidature a été acceptée. Nous vous contacterons prochainement pour discuter des prochaines étapes.\n\nCordialement,\nL'équipe RH")
            ->html("<p>Bonjour <strong>$candidateName</strong>,</p>
                  <p>Nous sommes heureux de vous informer que votre candidature a été <strong>acceptée</strong>.</p>
                  <p>Nous vous contacterons prochainement pour discuter des prochaines étapes.</p>
                  <p>Cordialement,<br>L'équipe RH</p>");

        $this->mailer->send($email);
    }

    // Méthode pour l'email de refus
    public function sendRefusEmail(string $recipientEmail, string $candidateName)
    {
        $email = (new Email())
            ->from('myriambenabdallah17@gmail.com')
            ->to($recipientEmail)
            ->subject('Information concernant votre candidature')
            ->text("Bonjour $candidateName,\n\nNous vous remercions pour l'intérêt que vous portez à notre entreprise. Après étude attentive de votre dossier, nous regrettons de vous informer que votre candidature n'a pas été retenue pour ce poste.\n\nNous vous souhaitons bonne chance dans vos futures recherches.\n\nCordialement,\nL'équipe RH")
            ->html("<p>Bonjour <strong>$candidateName</strong>,</p>
                  <p>Nous vous remercions pour l'intérêt que vous portez à notre entreprise.</p>
                  <p>Après étude attentive de votre dossier, nous regrettons de vous informer que votre candidature n'a pas été retenue pour ce poste.</p>
                  <p>Nous vous souhaitons bonne chance dans vos futures recherches.</p>
                  <p>Cordialement,<br>L'équipe RH</p>");

        $this->mailer->send($email);
    }
}
