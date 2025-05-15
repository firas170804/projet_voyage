<?php

namespace App\Controller;

use App\Entity\Vol;
use App\Form\VolType;
use App\Repository\VolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/vol')]
final class VolController extends AbstractController
{
    #[Route(name: 'app_vol_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, VolRepository $volRepository): Response
    {
        $query = $volRepository->createQueryBuilder('v')->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // page actuelle
            3 // nombre d'éléments par page
        );
    
        return $this->render('vol/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    
    // Autres actions (new, edit, delete, show)

    
    #[Route('/new', name: 'app_vol_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    // Crée une nouvelle instance de Vol
    $vol = new Vol();

    // Crée le formulaire à partir de VolType
    $form = $this->createForm(VolType::class, $vol);
    $form->handleRequest($request);

    // Vérifie si le formulaire a été soumis et est valide
    if ($form->isSubmitted() && $form->isValid()) {

        // À ce stade, Symfony a déjà associé les objets Compagnie, Avion et Aéroport à $vol
        // Tu peux les afficher pour debug si tu veux :
        // dd($vol->getCompagnie(), $vol->getAeroport(), $vol->getAvion());

        // Enregistre dans la base
        $entityManager->persist($vol);
        $entityManager->flush();

        // Redirection après succès
        return $this->redirectToRoute('app_vol_index', [], Response::HTTP_SEE_OTHER);
    }

    // Affiche le formulaire dans le template Twig
    return $this->render('vol/new.html.twig', [
        'vol' => $vol,
        'form' => $form->createView(), // Important : createView() ici
    ]);
}

    #[Route('/{id}', name: 'app_vol_show', methods: ['GET'])]
    public function show(Vol $vol): Response
    {
        return $this->render('vol/show.html.twig', [
            'vol' => $vol,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vol_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
{
    // Crée le formulaire en liant l'objet existant $vol
    $form = $this->createForm(VolType::class, $vol);
    $form->handleRequest($request);

    // Si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Pas besoin de persist() car $vol est déjà géré par Doctrine
        $entityManager->flush();

        // Redirection vers la liste des vols
        return $this->redirectToRoute('app_vol_index', [], Response::HTTP_SEE_OTHER);
    }

    // Affiche le formulaire avec les valeurs pré-remplies
    return $this->render('vol/edit.html.twig', [
        'vol' => $vol,
        'form' => $form->createView(), // Toujours createView() pour passer au template
    ]);
}


    #[Route('/{id}', name: 'app_vol_delete', methods: ['POST'])]
    public function delete(Request $request, Vol $vol, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vol->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vol);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vol_index', [], Response::HTTP_SEE_OTHER);
    }


}
