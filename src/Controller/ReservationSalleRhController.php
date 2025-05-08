<?php
namespace App\Controller;

use App\Entity\ReservationSalle;
use App\Repository\ReservationSalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rh/reservation-salle')]
final class ReservationSalleRhController extends AbstractController
{
    #[Route(name: 'app_rh_reservation_salle_index', methods: ['GET'])]
    public function index(Request $request, ReservationSalleRepository $reservationSalleRepository): Response
    {
        // Récupération des paramètres de la requête
        $statut = $request->query->get('statut');
        $salle = $request->query->get('salle');
        $search = $request->query->get('search');
        $sort = $request->query->get('sort', 'DateReservation');
        $direction = $request->query->get('direction', 'ASC');

        // Liste des champs autorisés pour le tri
        $allowedSortFields = ['DateReservation', 'DureeReservation'];

        // Vérifier que le champ de tri est valide
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'DateReservation'; // Tri par défaut
        }

        $qb = $reservationSalleRepository->createQueryBuilder('r');

        // Application des filtres
        if ($statut) {
            $qb->andWhere('r.StatutReservation = :statut')
               ->setParameter('statut', $statut);
        }

        if ($salle) {
            $qb->andWhere('r.IdSalle = :salle')
               ->setParameter('salle', $salle);
        }

        if ($search) {
            $qb->andWhere('r.IdEmploye.nom LIKE :search OR r.IdEmploye.prenom LIKE :search')
               ->setParameter('search', "%$search%");
        }

        // Application du tri
        $qb->orderBy('r.' . $sort, $direction);

        // Exécution de la requête
        $reservations = $qb->getQuery()->getResult();

        return $this->render('reservation_salle/indexRH.html.twig', [
            'reservation_salles' => $reservations,
            'statut' => $statut,
            'salle' => $salle,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction
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
