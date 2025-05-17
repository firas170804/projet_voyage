<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\VolRepository;
use Symfony\Component\Security\Core\Security; 
use Symfony\Component\Security\Core\Security as CoreSecurity;
use Symfony\Bundle\SecurityBundle\Security as BundleSecurity;
use App\Service\ReservationMailer;



#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos réservations.');
        }

        $reservations = $reservationRepository->findBy(['passager' => $user]);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }



   #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        VolRepository $volRepository,
        Security $security,
        ReservationMailer $mailer
    ): Response {
        $vol = $volRepository->find($id);

        if (!$vol) {
            throw $this->createNotFoundException('Vol non trouvé');
        }

        $avion = $vol->getAvion();

        if (!$avion) {
            throw $this->createNotFoundException('Aucun avion associé à ce vol');
        }

        if ($avion->getCapacite() <= 0) {
            $this->addFlash('danger', 'Aucune place disponible pour ce vol.');
            return $this->redirectToRoute('app_vol_index');
        }

        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour réserver.');
        }

        $reservation = new Reservation();
        $reservation->setVol($vol);
        $reservation->setPassager($user);
        $reservation->setDateReservation(new \DateTime());

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avion->setCapacite($avion->getCapacite() - 1);

            $entityManager->persist($reservation);
            $entityManager->flush();

            $mailer->sendConfirmation(
                $user->getEmail(),
                method_exists($user, 'getNom') ? $user->getNom() : $user->getUserIdentifier(),
                method_exists($user, 'getprenom') ? $user->getPrenom() : $user->getUserIdentifier(),
                $vol->getHeurDepart(),
                $vol->getDate(),
                $vol->getDepart(),
                $vol->getDestination()
            );

            $this->addFlash('success', 'Réservation effectuée avec succès !');
            return $this->redirectToRoute('app_vol_index');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
            'vol' => $vol,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
