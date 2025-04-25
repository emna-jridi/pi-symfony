<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

//    /**
//     * @return Formation[] Returns an array of Formation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Formation
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function searchByKeyword(?string $keyword, array $criteria = []): array
{
    $qb = $this->createQueryBuilder('f');

    if (!empty($keyword)) {
        $qb->where($qb->expr()->orX(
            $qb->expr()->like('LOWER(f.NomFormation)', ':keyword'),
            $qb->expr()->like('LOWER(f.ThemeFormation)', ':keyword')
        ))
        ->setParameter('keyword', '%' . strtolower($keyword) . '%');
    }

    // Add sorting
    if (!empty($criteria['sort'])) {
        $direction = !empty($criteria['direction']) ? $criteria['direction'] : 'ASC';
        $qb->orderBy('f.' . $criteria['sort'], $direction);
    } else {
        $qb->orderBy('f.idFormation', 'DESC');
    }

    return $qb->getQuery()->getResult();
}

public function findWithFilters(array $filters = []): array
{
    $qb = $this->createQueryBuilder('f');

    if (!empty($filters['theme'])) {
        $qb->andWhere('f.ThemeFormation = :theme')
           ->setParameter('theme', $filters['theme']);
    }

    if (!empty($filters['niveau'])) {
        $qb->andWhere('f.niveauDifficulte = :niveau')
           ->setParameter('niveau', $filters['niveau']);
    }

    if (!empty($filters['date'])) {
        $qb->andWhere('f.date = :date')
           ->setParameter('date', new \DateTime($filters['date']));
    }

    return $qb->getQuery()->getResult();
}

public function createQueryBuilderWithFilters(?string $keyword, array $criteria = [], array $filters = [])
{
    $qb = $this->createQueryBuilder('f');

    if (!empty($keyword)) {
        $qb->andWhere($qb->expr()->orX(
            $qb->expr()->like('LOWER(f.NomFormation)', ':keyword'),
            $qb->expr()->like('LOWER(f.ThemeFormation)', ':keyword')
        ))
        ->setParameter('keyword', '%' . strtolower($keyword) . '%');
    }

    if (!empty($filters['theme'])) {
        $qb->andWhere('f.ThemeFormation = :theme')
           ->setParameter('theme', $filters['theme']);
    }

    if (!empty($filters['niveau'])) {
        $qb->andWhere('f.niveauDifficulte = :niveau')
           ->setParameter('niveau', $filters['niveau']);
    }

    if (!empty($filters['date'])) {
        $qb->andWhere('f.date = :date')
           ->setParameter('date', new \DateTime($filters['date']));
    }

    // Add sorting
    if (!empty($criteria['sort'])) {
        $direction = !empty($criteria['direction']) ? $criteria['direction'] : 'ASC';
        $qb->orderBy('f.' . $criteria['sort'], $direction);
    } else {
        $qb->orderBy('f.idFormation', 'DESC');
    }

    return $qb;
}
}
