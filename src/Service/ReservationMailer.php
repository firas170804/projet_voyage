<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Psr\Log\LoggerInterface;

class ReservationMailer
{
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function sendConfirmation(
    string $toEmail, 
    string $nomClient,
    string $prenom, 
    \DateTime $heuredepart, 
    \DateTime $date
) {
    $htmlContent = <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Confirmation de réservation</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-bottom: 1px solid #e9ecef; }
            .content { padding: 20px; }
            .footer { margin-top: 20px; padding: 20px; text-align: center; font-size: 0.9em; color: #6c757d; border-top: 1px solid #e9ecef; }
            strong { color: #007bff; }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>Confirmation de réservation</h2>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>$nomClient $prenom</strong>,</p>
            <p>Votre réservation à <strong>{$heuredepart->format('H:i')}</strong> le <strong>{$date->format('d/m/Y')}</strong> a bien été enregistrée.</p>
            <p>Merci pour votre confiance.</p>
        </div>
        
        <div class="footer">
            <p>Cet email est envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>© {date('Y')} Votre Agence. Tous droits réservés.</p>
        </div>
    </body>
    </html>
    HTML;

    $email = (new Email())
        ->from('AgenceReservation@gmail.com')
        ->to($toEmail)
        ->subject('Confirmation de réservation')
        ->html($htmlContent);

    try {
        $this->mailer->send($email);
        $this->logger->info("Email de confirmation envoyé à $toEmail");
    } catch (\Throwable $e) {
        $this->logger->error("Erreur lors de l'envoi de l'e-mail : " . $e->getMessage());
        throw $e;
    }
}
}
