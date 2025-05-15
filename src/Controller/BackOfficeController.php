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
use App\Repository\UserRepository;

#[IsGranted('ROLE_ResponsableRH')]
final class BackOfficeController extends AbstractController
{
    #[Route('/back', name: 'app_back_office')]
    public function index(\App\Repository\UserRepository $userRepository): Response
    {
        $employes = $userRepository->findByRole('Employe');
        $nbEmployes = count($employes);
        $nbEmployesActifs = 0;
        $nbEmployesInactifs = 0;
        foreach ($employes as $employe) {
            if ($employe->getIsActive()) {
                $nbEmployesActifs++;
            } else {
                $nbEmployesInactifs++;
            }
        }
        $responsables = $userRepository->findByRole('ResponsableRH');
        $nbResponsables = count($responsables);
        $candidats = $userRepository->findByRole('Candidat');
        $nbCandidats = count($candidats);
        // Nouveaux candidats sur les 30 derniers jours
        $nbNouveauxCandidats = 0;
        $dateLimite = (new \DateTime())->modify('-30 days');
        foreach ($candidats as $candidat) {
            if (method_exists($candidat, 'getDateNaissanceUser')) { // Remplacer par la bonne date si besoin
                $date = $candidat->getDateNaissanceUser();
                if ($date && $date > $dateLimite) {
                    $nbNouveauxCandidats++;
                }
            }
        }
        return $this->render('back_office/index.html.twig', [
            'controller_name' => 'BackOfficeController',
            'nbEmployes' => $nbEmployes,
            'nbEmployesActifs' => $nbEmployesActifs,
            'nbEmployesInactifs' => $nbEmployesInactifs,
            'nbResponsables' => $nbResponsables,
            'nbCandidats' => $nbCandidats,
            'nbNouveauxCandidats' => $nbNouveauxCandidats,
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
                if ($user->getPassword() !== $currentPassword) {
                    $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                    return $this->redirectToRoute('app_back_settings');
                }

                // Vérifier que les nouveaux mots de passe correspondent
                if ($newPassword !== $confirmPassword) {
                    $this->addFlash('error', 'Les nouveaux mots de passe ne correspondent pas.');
                    return $this->redirectToRoute('app_back_settings');
                }

                // Mettre à jour le mot de passe en clair
                $user->setPassword($newPassword);
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
