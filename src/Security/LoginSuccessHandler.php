<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();
        $roles = $token->getRoleNames();

        // Redirection en fonction du rôle
        if (in_array('ROLE_ResponsableRh', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_back_office'));
        } elseif (in_array('ROLE_Employe', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_front_office'));
        } elseif (in_array('ROLE_Candidat', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('app_candidate'));
        }

        // Redirection par défaut si aucun rôle spécifique n'est trouvé
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
} 