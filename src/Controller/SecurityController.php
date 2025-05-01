<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\SignupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; 


final class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }
    #[Route('/login', name: 'app_login')]
public function login(AuthenticationUtils $authenticationUtils): Response
{
    $error = $authenticationUtils->getLastAuthenticationError();

    $lastUsername = $authenticationUtils->getLastUsername();
    return $this->render('security/login.html.twig', [
        'last_username' => $authenticationUtils->getLastUsername(),
        'error' => $authenticationUtils->getLastAuthenticationError(),
    ]);
}

#[Route('/logout', name: 'app_logout')]
public function logout(): void
{
    throw new \LogicException('This will be handled by Symfony.');
}

#[Route('/role-redirect', name: 'role_redirect')]
public function redirectByRole(): Response
{
    $roles = $this->getUser()->getRoles();

    return match (true) {
        in_array('ROLE_ADMIN', $roles) => $this->redirectToRoute('admin_dashboard'),
        in_array('ROLE_AGENCE', $roles) => $this->redirectToRoute('agence_dashboard'),
        in_array('ROLE_PASSAGER', $roles) => $this->redirectToRoute('passager_dashboard'),
        default => $this->redirectToRoute('app_login'),
    };
}
#[Route('/signup', name: 'app_signup')]
public function signup(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
{
    $user = new User();
    $form = $this->createForm(SignupType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(['ROLE_PASSAGER']); // rôle par défaut
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('app_login');
    }

    return $this->render('security/signup.html.twig', [
        'form' => $form->createView(),
    ]);
}


}
