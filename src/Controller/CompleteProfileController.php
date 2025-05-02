<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Psr\Log\LoggerInterface;

class CompleteProfileController extends AbstractController
{
    #[Route('/complete-profile', name: 'app_complete_profile')]
    public function completeProfile(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Si l'utilisateur a déjà rempli ces informations, le rediriger vers la page appropriée
        if ($user->getDateNaissanceUser() && $user->getTelephoneUser()) {
            if (in_array('ROLE_ResponsableRH', $user->getRoles())) {
                return $this->redirectToRoute('app_back_office');
            } else {
                return $this->redirectToRoute('app_front_office');
            }
        }

        $form = $this->createFormBuilder($user)
            ->add('dateNaissanceUser', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'required' => true,
                'html5' => true,
                'data' => new \DateTime(),
            ])
            ->add('telephoneUser', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => true,
                'attr' => [
                    'pattern' => '[0-9]{8}',
                    'maxlength' => '8',
                    'placeholder' => '12345678'
                ]
            ])
            ->add('adresseUser', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre adresse complète',
                    'minlength' => '5',
                    'maxlength' => '255'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Compléter le profil'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $logger->info('Formulaire soumis');
            
            if ($form->isValid()) {
                $logger->info('Formulaire valide');
                try {
                    // Log des données reçues
                    $logger->info('Données du formulaire:', [
                        'telephone' => $form->get('telephoneUser')->getData(),
                        'date' => $form->get('dateNaissanceUser')->getData() ? $form->get('dateNaissanceUser')->getData()->format('Y-m-d') : 'null',
                        'adresse' => $form->get('adresseUser')->getData()
                    ]);

                    // Convertir le numéro de téléphone en float
                    $telephoneStr = $form->get('telephoneUser')->getData();
                    if (!empty($telephoneStr)) {
                        $user->setTelephoneUser((float) $telephoneStr);
                        $logger->info('Téléphone défini:', ['telephone' => (float) $telephoneStr]);
                    }

                    // S'assurer que la date est bien définie
                    $dateNaissance = $form->get('dateNaissanceUser')->getData();
                    if ($dateNaissance instanceof \DateTime) {
                        $user->setDateNaissanceUser($dateNaissance);
                        $logger->info('Date définie:', ['date' => $dateNaissance->format('Y-m-d')]);
                    }

                    // Définir l'adresse
                    $adresse = $form->get('adresseUser')->getData();
                    if (!empty($adresse)) {
                        $user->setAdresseUser($adresse);
                        $logger->info('Adresse définie:', ['adresse' => $adresse]);
                    }

                    // Persister les changements
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $logger->info('Données persistées avec succès');

                    $this->addFlash('success', 'Votre profil a été mis à jour avec succès !');

                    // Log des données de l'utilisateur après sauvegarde
                    $logger->info('Données utilisateur après sauvegarde:', [
                        'telephone' => $user->getTelephoneUser(),
                        'date' => $user->getDateNaissanceUser() ? $user->getDateNaissanceUser()->format('Y-m-d') : 'null',
                        'adresse' => $user->getAdresseUser()
                    ]);

                    // Rediriger vers la page appropriée selon le rôle
                    if (in_array('ROLE_ResponsableRH', $user->getRoles())) {
                        return $this->redirectToRoute('app_back_office');
                    } else {
                        return $this->redirectToRoute('app_front_office');
                    }
                } catch (\Exception $e) {
                    $logger->error('Erreur lors de la sauvegarde:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $this->addFlash('error', 'Une erreur est survenue lors de la mise à jour de votre profil : ' . $e->getMessage());
                }
            } else {
                $logger->error('Formulaire invalide:', [
                    'errors' => $form->getErrors(true, false)
                ]);
                $this->addFlash('error', 'Le formulaire contient des erreurs. Veuillez les corriger.');
            }
        }

        return $this->render('complete_profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
} 