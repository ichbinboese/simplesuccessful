<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    #[Route('/login', name:'app_login')]
    public function login(AuthenticationUtils $authUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_app');
        }

        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name:'app_logout')]
    public function logout(): void
    {
        // Handled by firewall
        throw new \Exception('This should be intercepted by the firewall.');
    }

        #[Route('/forgot-password', name: 'app_forgot_password_request', methods: ['GET','POST'])]
    public function forgotPasswordRequest(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = trim((string) $request->request->get('email', ''));

            // TODO: Hier später echten Reset-Flow einbauen (Token generieren, Mail versenden)
            // WICHTIG: Immer gleich antworten, um Enumeration zu vermeiden.
            return $this->redirectToRoute('app_forgot_password_check_email');
        }

        return $this->render('security/forgot_password_request.html.twig');
    }

    #[Route('/forgot-password/check-email', name: 'app_forgot_password_check_email', methods: ['GET'])]
    public function forgotPasswordCheckEmail(): Response
    {
        return $this->render('security/forgot_password_check_email.html.twig');
    }
}
