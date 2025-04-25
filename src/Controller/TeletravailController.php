<?php

namespace App\Controller;

use App\Entity\Teletravail;
use App\Form\TeletravailType;
use App\Repository\TeletravailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Knp\Snappy\Pdf;

#[Route('/teletravail')]
final class TeletravailController extends AbstractController
{
    #[Route(name: 'app_teletravail_index', methods: ['GET'])]
    public function index(Request $request, TeletravailRepository $teletravailRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $employeId = $user->getIdUser();
        
        // Récupérer les paramètres de recherche, filtre et tri
        $search = $request->query->get('search', '');
        $statut = $request->query->get('statut', '');
        $sort = $request->query->get('sort', 'DateDemandeTT');
        $order = $request->query->get('order', 'DESC');

        // Valider le champ de tri pour éviter les injections
        $validSortFields = ['DateDemandeTT', 'DateDebutTT', 'DateFinTT'];
        if (!in_array($sort, $validSortFields)) {
            $sort = 'DateDemandeTT';
        }

        // Valider l'ordre de tri
        if (!in_array(strtoupper($order), ['ASC', 'DESC'])) {
            $order = 'DESC';
        }

        // Récupérer les demandes avec recherche, filtre et tri
        $teletravails = $teletravailRepository->findByCriteria(
            $employeId,
            $search,
            $statut,
            $sort,
            $order
        );

        return $this->render('teletravail/index.html.twig', [
            'teletravails' => $teletravails,
            'search' => $search,
            'statut' => $statut,
            'sort' => $sort,
            'order' => $order,
        ]);
    }

    #[Route('/new', name: 'app_teletravail_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour créer une demande de télétravail.');
        }

        $teletravail = new Teletravail();
        $teletravail->setIdEmploye($user->getIdUser());
        $teletravail->setDateDemandeTT(new \DateTime());
        $teletravail->setStatutTT('Traitement');

        $form = $this->createForm(TeletravailType::class, $teletravail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateDebut = $teletravail->getDateDebutTT();
            $dateFin = $teletravail->getDateFinTT();
            $today = new \DateTime();
            $today->setTime(0, 0);

            if ($dateDebut < $today) {
                $this->addFlash('error', 'La date de début ne peut pas être dans le passé.');
            } elseif ($dateFin <= $dateDebut) {
                $this->addFlash('error', 'La date de fin doit être postérieure à la date de début.');
            } elseif ($dateDebut->diff($dateFin)->days > 30) {
                $this->addFlash('error', 'La durée de télétravail ne peut pas dépasser 30 jours.');
            } else {
                $entityManager->persist($teletravail);
                $entityManager->flush();

                $this->addFlash('success', 'Demande de télétravail envoyée avec succès.');
                return $this->redirectToRoute('app_teletravail_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('teletravail/new.html.twig', [
            'teletravail' => $teletravail,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{IdTeletravail}', name: 'app_teletravail_show', methods: ['GET'])]
    public function show(Teletravail $teletravail): Response
    {
        $this->checkTeletravailOwnership($teletravail);

        return $this->render('teletravail/show.html.twig', [
            'teletravail' => $teletravail,
        ]);
    }

    #[Route('/{IdTeletravail}/edit', name: 'app_teletravail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teletravail $teletravail, EntityManagerInterface $entityManager): Response
    {
        $this->checkTeletravailOwnership($teletravail);

        $form = $this->createForm(TeletravailType::class, $teletravail);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'Demande de télétravail modifiée avec succès.');
            return $this->redirectToRoute('app_teletravail_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('teletravail/edit.html.twig', [
            'teletravail' => $teletravail,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{IdTeletravail}', name: 'app_teletravail_delete', methods: ['POST'])]
    public function delete(Request $request, Teletravail $teletravail, EntityManagerInterface $entityManager): Response
    {
        $this->checkTeletravailOwnership($teletravail);

        if ($this->isCsrfTokenValid('delete'.$teletravail->getIdTeletravail(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($teletravail);
            $entityManager->flush();

            $this->addFlash('success', 'Demande de télétravail supprimée avec succès.');
        }

        return $this->redirectToRoute('app_teletravail_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{IdTeletravail}/pdf', name: 'app_teletravail_pdf', methods: ['GET'])]
    public function exportPdf(Teletravail $teletravail, Pdf $pdf): Response
    {
        $this->checkTeletravailOwnership($teletravail);

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

    private function checkTeletravailOwnership(Teletravail $teletravail): void
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        if ($teletravail->getIdEmploye() !== $user->getIdUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette demande de télétravail.');
        }
    }
}