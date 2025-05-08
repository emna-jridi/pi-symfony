<?php

namespace App\Controller;

use App\Entity\ReservationSalle;
use App\Form\ReservationSalleType;
use App\Repository\ReservationSalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormError;

#[Route('/reservation/salle')]
final class ReservationSalleController extends AbstractController
{
    #[Route(name: 'app_reservation_salle_index', methods: ['GET'])]
    public function index(ReservationSalleRepository $reservationSalleRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // N'afficher que les réservations de l'utilisateur connecté
        $employeId = $user->getIdUser();
        
        return $this->render('reservation_salle/index.html.twig', [
            'reservation_salles' => $reservationSalleRepository->findBy(['IdEmploye' => $employeId]),
        ]);
    }

    #[Route('/new', name: 'app_reservation_salle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour créer une réservation.');
        }

        $reservationSalle = new ReservationSalle();
        // Définir automatiquement l'utilisateur connecté comme l'employé qui réserve
        $reservationSalle->setIdEmploye($user->getIdUser());
        // Définir le statut par défaut
        $reservationSalle->setStatutReservation('En attente');
        
        $form = $this->createForm(ReservationSalleType::class, $reservationSalle);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $hasErrors = false;

            $salle = $reservationSalle->getIdSalle();
            $date = $reservationSalle->getDateReservation();
            $duree = $reservationSalle->getDureeReservation();

            if (!$salle) {
                $form->get('IdSalle')->addError(new FormError("Veuillez sélectionner une salle."));
                $hasErrors = true;
            }

            if (!$date) {
                $form->get('DateReservation')->addError(new FormError("Veuillez entrer une date."));
                $hasErrors = true;
            } elseif ($date < new \DateTime()) {
                $form->get('DateReservation')->addError(new FormError("La date ne peut pas être dans le passé."));
                $hasErrors = true;
            }

            if ($duree === null || $duree <= 0) {
                $form->get('DureeReservation')->addError(new FormError("La durée doit être supérieure à 0."));
                $hasErrors = true;
            }

            if (!$hasErrors && $form->isValid()) {
                $entityManager->persist($reservationSalle);
                $entityManager->flush();

                $this->addFlash('success', 'Réservation créée avec succès !');
                return $this->redirectToRoute('app_reservation_salle_index');
            }
        }

        return $this->render('reservation_salle/new.html.twig', [
            'reservation_salle' => $reservationSalle,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{IdReservation}', name: 'app_reservation_salle_show', methods: ['GET'])]
    public function show(ReservationSalle $reservationSalle): Response
    {
        $this->checkReservationOwnership($reservationSalle);

        return $this->render('reservation_salle/show.html.twig', [
            'reservation_salle' => $reservationSalle,
        ]);
    }

    #[Route('/{IdReservation}/edit', name: 'app_reservation_salle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationSalle $reservationSalle, EntityManagerInterface $entityManager): Response
    {
        $this->checkReservationOwnership($reservationSalle);

        $form = $this->createForm(ReservationSalleType::class, $reservationSalle);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $hasErrors = false;

            $salle = $reservationSalle->getIdSalle();
            $date = $reservationSalle->getDateReservation();
            $duree = $reservationSalle->getDureeReservation();
            $statut = $reservationSalle->getStatutReservation();

            if (!$salle) {
                $form->get('IdSalle')->addError(new FormError("Veuillez sélectionner une salle."));
                $hasErrors = true;
            }

            if (!$date) {
                $form->get('DateReservation')->addError(new FormError("Veuillez entrer une date."));
                $hasErrors = true;
            } elseif ($date < new \DateTime()) {
                $form->get('DateReservation')->addError(new FormError("La date ne peut pas être dans le passé."));
                $hasErrors = true;
            }

            if ($duree === null || $duree <= 0) {
                $form->get('DureeReservation')->addError(new FormError("La durée doit être supérieure à 0."));
                $hasErrors = true;
            }

            $statutsValides = ['En attente', 'Confirmée', 'Annulée'];
            if (!in_array($statut, $statutsValides)) {
                $form->get('StatutReservation')->addError(new FormError("Le statut est invalide."));
                $hasErrors = true;
            }

            if (!$hasErrors && $form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'Réservation mise à jour avec succès !');
                return $this->redirectToRoute('app_reservation_salle_index');
            }
        }

        return $this->render('reservation_salle/edit.html.twig', [
            'reservation_salle' => $reservationSalle,
            'form' => $form,
        ]);
    }

    #[Route('/{IdReservation}', name: 'app_reservation_salle_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationSalle $reservationSalle, EntityManagerInterface $entityManager): Response
    {
        $this->checkReservationOwnership($reservationSalle);

        if ($this->isCsrfTokenValid('delete' . $reservationSalle->getIdReservation(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservationSalle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_salle_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Vérifie que l'utilisateur connecté est bien le propriétaire de la réservation
     */
    private function checkReservationOwnership(ReservationSalle $reservationSalle): void
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifie que l'utilisateur est bien le propriétaire de la réservation
        if ($reservationSalle->getIdEmploye() !== $user->getIdUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette réservation.');
        }
    }
}