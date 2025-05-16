<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Passager;
use App\Repository\VolRepository;
use Symfony\Component\Security\Core\Security;
use App\Repository\ReservationRepository;
use App\Repository\CompagnieRepository;
use App\Repository\UserRepository;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/passager/dashboard', name: 'passager_dashboard')]
        public function dashboard1(): Response
        {
            return $this->render('passagerHome/index.html.twig');
    }
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
        public function dashboard2(VolRepository $volRepository, ReservationRepository $reservationRepository, CompagnieRepository $compagnieRepository, UserRepository $userRepository): Response
        {
            $totalVols = $volRepository->countVols();
            $totalReservations = $reservationRepository->countReservations();
            $totCompagnies = $compagnieRepository->countCompagnies();
            $totalUsers = $userRepository->countUsers();
            $latestReservations = $reservationRepository->findLatestReservations();
            $latestVols = $volRepository->findLatestVols();


            return $this->render('adminHome/index.html.twig', [
                'total_vols' => $totalVols,
                'total_reservations' => $totalReservations,
                'total_compagnies' => $totCompagnies,
                'total_users' => $totalUsers,
                'latest_reservations' => $latestReservations,
                'latest_vols' => $latestVols,
            ]);
    }
    #[Route('/agence/dashboard', name: 'agence_dashboard')]
        public function dashboard3(): Response
        {
            
            return $this->render('agenceHome/index.html.twig');
    }
    #[Route('compte/{id}/edit', name: 'app_compte')]
    public function edit(Passager $passager)
    {
        
    }
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('sign_in/login.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/signup', name: 'app_signup')]
    public function signup(): Response
    {
        return $this->render('sign_in/signup.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
