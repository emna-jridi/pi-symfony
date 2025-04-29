<?php

namespace App\Services;

use App\Entity\Offreemploi;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;

class StatistiquesOffresService
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Récupère les données de candidatures par offre
     * 
     * @return array
     */
    public function getCandidaturesParOffre(): array
    {
        $offres = $this->entityManager->getRepository(Offreemploi::class)->findAll();
        
        $data = [
            'labels' => [],
            'data' => [],
            'offres' => $offres,
            'offrePopulaire' => null,
            'totalCandidatures' => 0,
            'totalOffres' => count($offres)
        ];
        
        $maxCandidatures = 0;
        
        foreach ($offres as $offre) {
            $nbCandidatures = count($offre->getCandidatures());
            $data['labels'][] = $offre->getTitre();
            $data['data'][] = $nbCandidatures;
            $data['totalCandidatures'] += $nbCandidatures;
            
            if ($nbCandidatures > $maxCandidatures) {
                $maxCandidatures = $nbCandidatures;
                $data['offrePopulaire'] = $offre;
            }
        }
        
        return $data;
    }
    
    /**
     * Récupère les statistiques par statut de candidature
     * 
     * @return array
     */
    public function getStatutsCandidatures(): array
    {
        $candidatures = $this->entityManager->getRepository(Candidature::class)->findAll();
        
        $statuts = [
            'En cours' => 0,
            'Acceptée' => 0,
            'Disqualifiée' => 0
        ];
        
        foreach ($candidatures as $candidature) {
            $statut = $candidature->getStatut();
            if ($statut) {
                $statutLabel = $statut->value;
                if (isset($statuts[$statutLabel])) {
                    $statuts[$statutLabel]++;
                }
            }
        }
        
        return [
            'labels' => array_keys($statuts),
            'data' => array_values($statuts)
        ];
    }
}