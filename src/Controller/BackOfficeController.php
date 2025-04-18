<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\BackOfficeUserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[IsGranted('ROLE_ResponsableRH')]
final class BackOfficeController extends AbstractController
{
    #[Route('/back', name: 'app_back_office')]
    public function index(): Response
    {
        return $this->render('back_office/index.html.twig', [
            'controller_name' => 'BackOfficeController',
        ]);
    }

    #[Route('/back/settings', name: 'app_back_settings')]
    public function settings(
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(BackOfficeUserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();
            
            // Vérifier si l'utilisateur tente de changer son mot de passe
            if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
                // Vérifier que le mot de passe actuel est correct
                if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                    $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                    return $this->redirectToRoute('app_back_settings');
                }

                // Vérifier que les nouveaux mots de passe correspondent
                if ($newPassword !== $confirmPassword) {
                    $this->addFlash('error', 'Les nouveaux mots de passe ne correspondent pas.');
                    return $this->redirectToRoute('app_back_settings');
                }

                // Mettre à jour le mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
            }
            
            $entityManager->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('app_back_settings');
        }

        return $this->render('back_office/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
