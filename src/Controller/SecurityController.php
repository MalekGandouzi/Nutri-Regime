<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(Request $request, Connection $conn, SessionInterface $session): Response
    {
        
        if ($session->get('admin_logged')) {
            return $this->redirectToRoute('admin_regime_list');
        }

        $error = null;

        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            $admin = $conn->fetchAssociative(
                'SELECT * FROM admin WHERE username = ?',
                [$username]
            );

            if ($admin && $admin['password'] === $password) {
                $session->set('admin_logged', true);
                $session->set('admin_username', $username);

                return $this->redirectToRoute('admin_regime_list');
            } else {
                $error = 'Identifiants incorrects.';
            }
        }

        return $this->render('security/login.html.twig', [
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('app_login');
    }
}
