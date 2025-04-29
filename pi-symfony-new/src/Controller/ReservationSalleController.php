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

#[Route('/reservation/salle')]
final class ReservationSalleController extends AbstractController
{
    #[Route(name: 'app_reservation_salle_index', methods: ['GET'])]
    public function index(ReservationSalleRepository $reservationSalleRepository): Response
    {
        return $this->render('reservation_salle/index.html.twig', [
            'reservation_salles' => $reservationSalleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_salle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationSalle = new ReservationSalle();
        $form = $this->createForm(ReservationSalleType::class, $reservationSalle);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $hasErrors = false;

            $employe = $reservationSalle->getIdEmploye();
            $salle = $reservationSalle->getIdSalle();
            $date = $reservationSalle->getDateReservation();
            $duree = $reservationSalle->getDureeReservation();
            $statut = $reservationSalle->getStatutReservation();

            if (!$employe) {
                $form->get('IdEmploye')->addError(new \Symfony\Component\Form\FormError("Veuillez sélectionner un employé."));
                $hasErrors = true;
            }

            if (!$salle) {
                $form->get('IdSalle')->addError(new \Symfony\Component\Form\FormError("Veuillez sélectionner une salle."));
                $hasErrors = true;
            }

            if (!$date) {
                $form->get('DateReservation')->addError(new \Symfony\Component\Form\FormError("Veuillez entrer une date."));
                $hasErrors = true;
            } elseif ($date < new \DateTime()) {
                $form->get('DateReservation')->addError(new \Symfony\Component\Form\FormError("La date ne peut pas être dans le passé."));
                $hasErrors = true;
            }

            if ($duree === null || $duree <= 0) {
                $form->get('DureeReservation')->addError(new \Symfony\Component\Form\FormError("La durée doit être supérieure à 0."));
                $hasErrors = true;
            }

            if (!$hasErrors && $form->isValid()) {
                $entityManager->persist($reservationSalle);
                $entityManager->flush();

                $this->addFlash('success', 'Réservation créée avec succès !');
                return $this->redirectToRoute('app_reservation_salle_index');
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Le statut "en attente" sera automatiquement pris en charge
            $em->persist($reservation);
            $em->flush();
    
            return $this->redirectToRoute('reservation_salle_index');
        }
    
        return $this->render('reservation_salle/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{IdReservation}', name: 'app_reservation_salle_show', methods: ['GET'])]
    public function show(ReservationSalle $reservationSalle): Response
    {
        return $this->render('reservation_salle/show.html.twig', [
            'reservation_salle' => $reservationSalle,
        ]);
    }

    #[Route('/{IdReservation}/edit', name: 'app_reservation_salle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationSalle $reservationSalle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationSalleType::class, $reservationSalle);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $hasErrors = false;

            $employe = $reservationSalle->getIdEmploye();
            $salle = $reservationSalle->getIdSalle();
            $date = $reservationSalle->getDateReservation();
            $duree = $reservationSalle->getDureeReservation();
            $statut = $reservationSalle->getStatutReservation();

            if (!$employe) {
                $form->get('IdEmploye')->addError(new \Symfony\Component\Form\FormError("Veuillez sélectionner un employé."));
                $hasErrors = true;
            }

            if (!$salle) {
                $form->get('IdSalle')->addError(new \Symfony\Component\Form\FormError("Veuillez sélectionner une salle."));
                $hasErrors = true;
            }

            if (!$date) {
                $form->get('DateReservation')->addError(new \Symfony\Component\Form\FormError("Veuillez entrer une date."));
                $hasErrors = true;
            } elseif ($date < new \DateTime()) {
                $form->get('DateReservation')->addError(new \Symfony\Component\Form\FormError("La date ne peut pas être dans le passé."));
                $hasErrors = true;
            }

            if ($duree === null || $duree <= 0) {
                $form->get('DureeReservation')->addError(new \Symfony\Component\Form\FormError("La durée doit être supérieure à 0."));
                $hasErrors = true;
            }

            $statutsValides = ['En attente', 'Confirmée', 'Annulée'];
            if (!in_array($statut, $statutsValides)) {
                $form->get('StatutReservation')->addError(new \Symfony\Component\Form\FormError("Le statut est invalide."));
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
        if ($this->isCsrfTokenValid('delete' . $reservationSalle->getIdReservation(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservationSalle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_salle_index', [], Response::HTTP_SEE_OTHER);
    }
}
