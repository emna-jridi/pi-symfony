<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GoogleController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google')]  

    public function connectAction(ClientRegistry $clientRegistry): Response
    {
        
        return $clientRegistry
            ->getClient('google')
            ->redirect(['email', 'profile'
]);
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        try {
            /** @var \League\OAuth2\Client\Provider\GoogleUser $googleUser */
            $googleUser = $clientRegistry->getClient('google')->fetchUser();

            // Rechercher si l'utilisateur existe déjà
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['emailUser' => $googleUser->getEmail()]);

            if (!$existingUser) {
                // Créer un nouvel utilisateur
                $user = new User();
                $user->setEmailUser($googleUser->getEmail());
                $user->setNomUser($googleUser->getLastName() ?? '');
                $user->setPrenomUser($googleUser->getFirstName() ?? '');
                $user->setRole('Employe'); // Enlever le préfixe ROLE_
                $user->setIsActive(true);
                
                // Définir des valeurs temporaires pour les champs obligatoires
                $user->setDateNaissanceUser(new \DateTime('2000-01-01')); // Date temporaire
                $user->setTelephoneUser(0); // Numéro temporaire
                $user->setAdresseUser('À compléter'); // Adresse temporaire
                
                // Générer un mot de passe aléatoire
                $randomPassword = bin2hex(random_bytes(16));
                $user->setPassword($randomPassword);
                
                $entityManager->persist($user);
                $entityManager->flush();
                
                $existingUser = $user;
            }

            // Créer le token d'authentification
            $token = new UsernamePasswordToken(
                $existingUser,
                'main',
                $existingUser->getRoles()
            );

            // Obtenir le service de sécurité et définir le token
            $this->container->get('security.token_storage')->setToken($token);
            
            // Marquer la session comme initialisée
            $request->getSession()->set('_security_main', serialize($token));

            // Vérifier si l'utilisateur a besoin de compléter son profil
            if (!$existingUser->getDateNaissanceUser() || !$existingUser->getTelephoneUser() || !$existingUser->getAdresseUser()) {
                // Stocker un message flash pour informer l'utilisateur
                $this->addFlash('info', 'Veuillez compléter votre profil pour continuer.');
                return $this->redirectToRoute('app_complete_profile');
            }

            // Redirection en fonction du rôle
            if (in_array('ROLE_ResponsableRH', $existingUser->getRoles())) {
                return $this->redirectToRoute('app_back_office');
            } elseif (in_array('ROLE_Employe', $existingUser->getRoles())) {
                return $this->redirectToRoute('app_front_office');
            } else {
                // Si aucun rôle correspondant, rediriger vers la page de login
                return $this->redirectToRoute('app_login');
            }

        } catch (IdentityProviderException $e) {
            // Une erreur est survenue
            return new Response($e->getMessage(), 401);
        }
    }
} 