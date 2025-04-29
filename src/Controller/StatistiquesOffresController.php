<?php

namespace App\Controller;

use App\Services\StatistiquesOffresService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class StatistiquesOffresController extends AbstractController
{
    private $statistiquesService;

    public function __construct(StatistiquesOffresService $statistiquesService)
    {
        $this->statistiquesService = $statistiquesService;
    }
    #[Route('/statistiques/offres', name: 'app_statistiques_offres')]
    public function index(): Response
    {
        return $this->render('statistiques_offres/index.html.twig', [
            'controller_name' => 'StatistiquesOffresController',
        ]);
    }
    #[Route('/statistiques', name: 'app_statistiques')]
    public function indexe(): Response
    {
        // Récupérer les données statistiques depuis le service
        $data = $this->statistiquesService->getCandidaturesParOffre();
        
        return $this->render('offreemploi/statistiquesoffre.html.twig', [
            'controller_name' => 'StatistiquesOffresController',  // Add this line
            'labels' => json_encode($data['labels']),
            'data' => json_encode($data['data']),
            'offres' => $data['offres'],
            'offrePopulaire' => $data['offrePopulaire'],
            'totalCandidatures' => $data['totalCandidatures'],
            'totalOffres' => $data['totalOffres'],
        ]);
    }
}
