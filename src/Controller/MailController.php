<?php

namespace App\Controller;


use App\Service\ReservationMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


final class MailController extends AbstractController
{
   #[Route('/test-email')]
public function sendEmail(MailerInterface $mailer): Response
{
    $email = (new Email())
        ->from('mahjouba562@gmail.com')
        ->to('benmansourfiras17@gmail.com')
        ->subject('Test Email')
        ->text('Ceci est un test.')
        ->html('<p>Ceci est un <strong>test</strong> depuis Mailtrap</p>');

    $mailer->send($email);

    return new Response('✅ Email envoyé !');
}


}
