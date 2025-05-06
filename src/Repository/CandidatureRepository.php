<?php

namespace App\Repository;

use App\Entity\Candidature;
use App\Entity\Offreemploi;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Candidature>
 */
class CandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidature::class);
    }

//    /**
//     * @return Candidature[] Returns an array of Candidature objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Candidature
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findByStatutAndOffre($statut, ?Offreemploi $offre = null)
{
    $queryBuilder = $this->createQueryBuilder('c')
        ->where('c.statut = :statut')
        ->setParameter('statut', $statut)
        ->orderBy('c.dateCandidature', 'DESC');
    
    if ($offre) {
        $queryBuilder
            ->andWhere('c.offre = :offre')
            ->setParameter('offre', $offre);
    }

    return $queryBuilder->getQuery()->getResult();
}
public function findByOffreId(int $offreId): array
{
    return $this->createQueryBuilder('c')
        ->where('c.offre = :id')
        ->setParameter('id', $offreId)
        ->getQuery()
        ->getResult();
}
public function findByUser($user, ?string $statut = null): array
{
    $queryBuilder = $this->createQueryBuilder('c')
        ->where('c.candidat = :user')
        ->setParameter('user', $user)
        ->orderBy('c.dateCandidature', 'DESC');
    
    if ($statut) {
        $queryBuilder
            ->andWhere('c.statut = :statut')
            ->setParameter('statut', $statut);
    }

    return $queryBuilder->getQuery()->getResult();
}
}
