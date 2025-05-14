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


#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route(name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
     public function new(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        VolRepository $volRepository,
        Security $security
    ): Response {
        // Récupérer le vol via l'id dans l'URL
        $vol = $volRepository->find($id);
        
        if (!$vol) {
            throw $this->createNotFoundException('Vol non trouvé');
        }

        // Récupérer la date du vol
        $dateVol = $vol->getDate();
        // Vous pouvez maintenant utiliser $dateVol pour des traitements supplémentaires

        // Vous pouvez aussi récupérer d'autres informations liées à la réservation, par exemple
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour réserver.');
        }

        // Créer une nouvelle instance de Reservation
        $reservation = new Reservation();
        $reservation->setVol($vol); // Lier le vol sélectionné à la réservation
        $reservation->setPassager($user); // Lier l'utilisateur connecté à la réservation
        $reservation->setDateReservation($dateVol); 
        // Créer le formulaire
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request); // Traiter la soumission du formulaire

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister la réservation dans la base de données
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Ajouter un message flash de succès
            $this->addFlash('success', 'Réservation effectuée avec succès !');

            // Rediriger vers la liste des vols après la réservation
            return $this->redirectToRoute('app_vol_index');
        }

        // Si le formulaire n'est pas soumis ou n'est pas valide
        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
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
