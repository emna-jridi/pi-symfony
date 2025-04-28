<?php

namespace App\Controller;

use App\Entity\Teletravail;
use App\Repository\TeletravailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/rh/teletravail')]
final class TeletravailRHcontroller extends AbstractController
{
    #[Route('/', name: 'rh_teletravail_index', methods: ['GET'])]
    public function index(TeletravailRepository $teletravailRepository): Response
    {
        return $this->render('teletravail/indexRH.html.twig', [
            'teletravails' => $teletravailRepository->findAll(),
        ]);
    }

    #[Route('/{id}/traiter', name: 'rh_teletravail_traiter', methods: ['POST'])]
    public function traiter(Request $request, Teletravail $teletravail, EntityManagerInterface $em): Response
    {
        $nouveauStatut = $request->request->get('statut');

        if (!in_array($nouveauStatut, ['Accepté', 'Refusé'])) {
            $this->addFlash('error', 'Statut invalide.');
            return $this->redirectToRoute('rh_teletravail_index');
        }

        $teletravail->setStatutTT($nouveauStatut);
        $em->flush();

        $this->addFlash('success', 'Statut mis à jour avec succès.');
        return $this->redirectToRoute('rh_teletravail_index');
    }
}
