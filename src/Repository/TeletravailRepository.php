<?php

namespace App\Repository;

use App\Entity\Teletravail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Teletravail>
 */
class TeletravailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teletravail::class);
    }

    /**
     * Trouve les demandes de télétravail selon les critères donnés pour un employé
     *
     * @param int $employeId
     * @param string $search
     * @param string $statut
     * @param string $sort
     * @param string $order
     * @return Teletravail[]
     */
    public function findByCriteria(int $employeId, string $search, string $statut, string $sort, string $order): array
    {
        return $this->findByCriteriaQuery($employeId, $search, $statut, $sort, $order)->getQuery()->getResult();
    }

    /**
     * Retourne une requête QueryBuilder pour les demandes de télétravail selon les critères donnés pour un employé
     *
     * @param int $employeId
     * @param string $search
     * @param string $statut
     * @param string $sort
     * @param string $order
     * @return QueryBuilder
     */
    public function findByCriteriaQuery(int $employeId, string $search, string $statut, string $sort, string $order): QueryBuilder
    {
        $validSortFields = [
            'DateDemandeTT' => 't.DateDemandeTT',
            'DateDebutTT' => 't.DateDebutTT',
            'DateFinTT' => 't.DateFinTT',
        ];
        $sortField = $validSortFields[$sort] ?? $validSortFields['DateDemandeTT'];

        $qb = $this->createQueryBuilder('t')
            ->where('t.IdEmploye = :employeId')
            ->setParameter('employeId', $employeId);

        // Recherche dynamique par RaisonTT
        if (!empty($search)) {
            $qb->andWhere('t.RaisonTT LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        // Filtre par statut
        if (!empty($statut)) {
            $qb->andWhere('t.StatutTT = :statut')
               ->setParameter('statut', $statut);
        }

        // Tri
        $qb->orderBy($sortField, $order);

        return $qb;
    }
    public function findByRHCriteria(string $search, string $statut, ?string $dateDebut, ?string $dateFin, string $sort, string $order): array
    {
        return $this->findByRHCriteriaQuery($search, $statut, $dateDebut, $dateFin, $sort, $order)->getQuery()->getResult();
    }
    
    /**
     * Retourne une requête QueryBuilder pour les demandes de télétravail selon les critères donnés pour RH
     *
     * @param string $search
     * @param string $statut
     * @param string|null $dateDebut
     * @param string|null $dateFin
     * @param string $sort
     * @param string $order
     * @return QueryBuilder
     */
    public function findByRHCriteriaQuery(string $search, string $statut, ?string $dateDebut, ?string $dateFin, string $sort, string $order): QueryBuilder
{
    $validSortFields = [
        'DateDemandeTT' => 't.DateDemandeTT',
        'DateDebutTT' => 't.DateDebutTT',
        'DateFinTT' => 't.DateFinTT',
    ];
    $sortField = $validSortFields[$sort] ?? $validSortFields['DateDemandeTT'];

    $qb = $this->createQueryBuilder('t');

    // Recherche dynamique par RaisonTT
    if (!empty($search)) {
        $qb->andWhere('t.RaisonTT LIKE :search')
           ->setParameter('search', '%' . $search . '%');
    }

    // Filtre par statut
    if (!empty($statut)) {
        $qb->andWhere('t.StatutTT = :statut')
           ->setParameter('statut', $statut);
    }

    // Tu peux aussi gérer le filtre par dates ici si tu veux :
    if ($dateDebut) {
        $qb->andWhere('t.DateDebutTT >= :dateDebut')
           ->setParameter('dateDebut', $dateDebut);
    }

    if ($dateFin) {
        $qb->andWhere('t.DateFinTT <= :dateFin')
           ->setParameter('dateFin', $dateFin);
    }

    // Tri sécurisé
    $qb->orderBy($sortField, $order);

    return $qb;
}

public function getTeletravailStatistics(): array
{
    return $this->createQueryBuilder('t')
        ->select('t.StatutTT as statut, COUNT(t.IdTeletravail) as count')
        ->groupBy('t.StatutTT')
        ->getQuery()
        ->getResult();
}
}