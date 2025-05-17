<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class ReservationMailer
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function sendConfirmation(
        string $toEmail, 
        string $nomClient,
        string $prenom, 
        \DateTimeInterface $heuredepart, 
        \DateTimeInterface $date,
        string $depart,
        string $destination
    ): void {
        $currentYear = date('Y');
        $formattedDate = $date->format('d/m/Y');
        $formattedHeureDepart = $heuredepart->format('H:i');

        $htmlContent = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>🌟 Confirmation de votre voyage</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f5f7ff;">
    <!-- En-tête avec couleur -->
    <div style="background-color: #4361ee; padding: 25px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">
            <span style="color: #f72585;">✈</span> Réservation confirmée !
        </h1>
    </div>
    
    <!-- Contenu principal -->
    <div style="background-color: white; padding: 25px; border-radius: 0 0 10px 10px;">
        <p style="color: #3a0ca3; font-size: 18px;">
            Bonjour <strong style="color: #4361ee;">$nomClient $prenom</strong>,
        </p>
        
        <p style="color: #333;">Votre réservation est validée ! Préparez-vous pour le décollage :</p>
        
        <!-- Carte de réservation -->
        <div style="background-color: #f8f9fa; border-left: 4px solid #f72585; padding: 15px; margin: 20px 0; border-radius: 0 8px 8px 0;">
            <div style="display: flex; align-items: center; margin: 10px 0; color: #333;">
                <span style="color: #4361ee; margin-right: 10px;">📅</span>
                <span>Date : <strong style="color: #4361ee;">$formattedDate</strong></span>
            </div>
            
            <div style="display: flex; align-items: center; margin: 10px 0; color: #333;">
                <span style="color: #4361ee; margin-right: 10px;">🕒</span>
                <span>Heure : <strong style="color: #4361ee;">$formattedHeureDepart</strong></span>
            </div>
            <div style="display: flex; align-items: center; margin: 10px 0; color: #333;">
                <span style="color: #4361ee; margin-right: 10px;">🛫</span>
                <span>Départ : <strong style="color: #4361ee;">$depart</strong></span>
            </div>
            <div style="display: flex; align-items: center; margin: 10px 0; color: #333;">
                <span style="color: #4361ee; margin-right: 10px;">🛬</span>
                <span>Destination : <strong style="color: #4361ee;">$destination</strong></span>
            </div>
            <div style="display: flex; align-items: center; margin: 10px 0; color: #333;">
                <span style="color: #4361ee; margin-right: 10px;">✅</span>
                <span>Statut : <strong style="color: #28a745;">Confirmé</strong></span>
            </div>
        </div>
        
        <p style="color: #333;">Nous vous recommandons :</p>
        <ul style="color: #333; padding-left: 20px;">
            <li>Arriver 2h avant le décollage</li>
            <li>Préparer vos documents de voyage</li>
            <li>Vérifier les informations de votre vol</li>
        </ul>
        
        <!-- Bouton d'action -->
        <div style="text-align: center; margin: 25px 0;">
            <a href="#" style="display: inline-block; background-color: #4361ee; color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; font-weight: bold;">
                Voir ma réservation
            </a>
        </div>
        
        <p style="color: #6c757d; font-style: italic;">
            À bientôt à bord !<br>
            <strong style="color: #3a0ca3;">L'équipe SkyTravel</strong>
        </p>
    </div>
    
    <!-- Pied de page -->
    <div style="text-align: center; color: #6c757d; font-size: 12px; margin-top: 20px;">
        <p>Cet email est envoyé automatiquement - Merci de ne pas y répondre</p>
        <p>© 2025 SkyTravel - Tous droits réservés</p>
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