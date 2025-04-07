<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Passager;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('compte/{id}/edit', name: 'app_compte')]
    public function edit(Passager $passager)
    {
        dd($passager);
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
