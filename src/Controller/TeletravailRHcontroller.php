<?php

namespace App\Controller;

use App\Entity\Teletravail;
use App\Repository\TeletravailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Snappy\Pdf;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/rh/teletravail')]
final class TeletravailRHcontroller extends AbstractController
{
    #[Route(name: 'rh_teletravail_index', methods: ['GET'])]
public function index(Request $request, TeletravailRepository $teletravailRepository): Response
{
    $search = $request->query->get('search', '');
    $statut = $request->query->get('statut', '');
    $dateDebut = $request->query->get('dateDebut', '');
    $dateFin = $request->query->get('dateFin', '');
    $sort = $request->query->get('sort', 'DateDemandeTT');
    $order = strtoupper($request->query->get('order', 'DESC'));

    $validSortFields = ['DateDemandeTT', 'DateDebutTT', 'DateFinTT'];
    if (!in_array($sort, $validSortFields)) {
        $sort = 'DateDemandeTT';
    }

    if (!in_array($order, ['ASC', 'DESC'])) {
        $order = 'DESC';
    }

    $queryBuilder = $teletravailRepository->findByRHCriteriaQuery($search, $statut, $dateDebut, $dateFin, $sort, $order);
    $results = $queryBuilder->getQuery()->getResult();

    return $this->render('teletravail/indexRH.html.twig', [
        'teletravails' => $results,
        'search' => $search,
        'statut' => $statut,
        'dateDebut' => $dateDebut,
        'dateFin' => $dateFin,
        'sort' => $sort,
        'order' => $order,
    ]);
}

    

    #[Route('/{id}/traiter', name: 'rh_teletravail_traiter', methods: ['POST'])]
    public function traiter(Request $request, Teletravail $teletravail, EntityManagerInterface $em): Response
    {
        $nouveauStatut = $request->request->get('statut');

        if (!in_array($nouveauStatut, ['Accepté', 'Refusé', 'Annulé'])) {
            $this->addFlash('error', 'Statut invalide.');
            return $this->redirectToRoute('rh_teletravail_index');
        }

        $teletravail->setStatutTT($nouveauStatut);
        $em->flush();

        $this->addFlash('success', 'Statut mis à jour avec succès.');
        return $this->redirectToRoute('rh_teletravail_index');
    }

    #[Route('/{id}', name: 'rh_teletravail_show', methods: ['GET'])]
    public function show(Teletravail $teletravail): Response
    {
        return $this->render('teletravail/showRH.html.twig', [
            'teletravail' => $teletravail,
        ]);
    }

    #[Route('/{id}/pdf', name: 'rh_teletravail_pdf', methods: ['GET'])]
    public function exportPdf(Teletravail $teletravail, Pdf $pdf): Response
    {
        $html = $this->renderView('teletravail/pdf.html.twig', [
            'teletravail' => $teletravail,
        ]);

        return new Response(
            $pdf->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="demande_teletravail_' . $teletravail->getIdTeletravail() . '.pdf"',
            ]
        );
    }
}
