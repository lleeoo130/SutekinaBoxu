<?php

namespace App\Controller;


use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        # Redirects user if already logged-in.
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        # Getting error messages:
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastEmail = $authenticationUtils->getLastUsername();

        # Getting the connexion form:
        $form = $this->createForm(LoginType::class, [
            'email' => $lastEmail
        ]);

        return $this->render('security/login.html.twig', [
            'last_email' => $lastEmail,
            'error' => $error,
            'form'  => $form->createView()
        ]);
    }
}