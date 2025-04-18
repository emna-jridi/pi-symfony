<?php

namespace App\Controller;

use App\Entity\ReservationSalle;
use App\Repository\ReservationSalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rh/reservation-salle')]
final class ReservationSalleRhController extends AbstractController
{
    #[Route(name: 'app_rh_reservation_salle_index', methods: ['GET'])]
    public function index(ReservationSalleRepository $reservationSalleRepository): Response
    {
        return $this->render('reservation_salle/indexRH.html.twig', [
            'reservation_salles' => $reservationSalleRepository->findAll(),
        ]);
    }

    #[Route('/{id}/valider', name: 'app_rh_reservation_salle_valider', methods: ['POST'])]
    public function valider(Request $request, ReservationSalle $reservationSalle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('valider' . $reservationSalle->getIdReservation(), $request->get('_token'))) {
            $reservationSalle->setStatutReservation('Confirmée');
            $entityManager->flush();

            $this->addFlash('success', 'Réservation confirmée.');
        }

        return $this->redirectToRoute('app_rh_reservation_salle_index');
    }

    #[Route('/{id}/annuler', name: 'app_rh_reservation_salle_annuler', methods: ['POST'])]
    public function annuler(Request $request, ReservationSalle $reservationSalle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('annuler' . $reservationSalle->getIdReservation(), $request->get('_token'))) {
            $reservationSalle->setStatutReservation('Annulée');
            $entityManager->flush();

            $this->addFlash('warning', 'Réservation annulée.');
        }

        return $this->redirectToRoute('app_rh_reservation_salle_index');
    }
}
