<?php

namespace App\Repository;

use App\Enum\Typecontrat;
use App\Enum\NiveauEtudes;
use App\Entity\Offreemploi;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Offreemploi>
 */
class OffreemploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offreemploi::class);
    }

//    /**
//     * @return Offreemploi[] Returns an array of Offreemploi objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offreemploi
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findByFiltres(?Typecontrat $typeContrat = null, ?NiveauEtudes $niveauEtudes = null)
{
    $queryBuilder = $this->createQueryBuilder('o')
        ->orderBy('o.dateCreation', 'DESC');
        
    // Filtrage par type de contrat
    if ($typeContrat) {
        $queryBuilder
            ->andWhere('o.typecontrat = :typeContrat')
            ->setParameter('typeContrat', $typeContrat);
    }
    
    // Filtrage par niveau d'études
    if ($niveauEtudes) {
        $queryBuilder
            ->andWhere('o.niveauEtudes = :niveauEtudes')
            ->setParameter('niveauEtudes', $niveauEtudes);
    }
    
    return $queryBuilder->getQuery()->getResult();
}
public function searchByTerm(string $term)
{
    $qb = $this->createQueryBuilder('o');
    
    if (!empty($term)) {
        $qb->andWhere('o.titre LIKE :term OR o.description LIKE :term OR o.localisation LIKE :term')
           ->setParameter('term', '%' . $term . '%');
    }
    
    // Tri par date de création (le plus récent d'abord)
    $qb->orderBy('o.dateCreation', 'DESC');
    
    return $qb->getQuery()->getResult();
}

}
