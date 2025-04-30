<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

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



public function createQueryBuilderWithFilters(string $keyword = '', array $criteria = [], array $filters = []): QueryBuilder
{
    $qb = $this->createQueryBuilder('f');

    if (!empty($keyword)) {
        $qb->andWhere('LOWER(f.NomFormation) LIKE LOWER(:keyword) OR LOWER(f.description) LIKE LOWER(:keyword)')
           ->setParameter('keyword', '%' . $keyword . '%');
    }

    if (!empty($filters['theme'])) {
        $themeMap = [
            'dev' => 'Développement',
            'commercial' => 'Commercial',
            'marketing' => 'Marketing',
            'design' => 'Design',
            'hr' => 'Ressources Humaines',
            'project_management' => 'Gestion de projet',
            'finance' => 'Finance'
        ];
        
        if (isset($themeMap[$filters['theme']])) {
            $qb->andWhere('f.ThemeFormation = :theme')
               ->setParameter('theme', $themeMap[$filters['theme']]);
        }
    }
    if (!empty($filters['niveau'])) {
        $niveauMap = [
            'debutant' => 'Débutant',
            'intermediaire' => 'Intermédiaire',
            'avance' => 'Avancé'
        ];
        
        if (isset($niveauMap[$filters['niveau']])) {
            $qb->andWhere('f.niveauDifficulte = :niveau')
               ->setParameter('niveau', $niveauMap[$filters['niveau']]);
        }
    }
    if (!empty($criteria['sort']) && !empty($criteria['direction'])) {
        $sortFieldMap = [
            'nomFormation' => 'NomFormation',
            'themeFormation' => 'ThemeFormation',
            'niveauDifficulte' => 'niveauDifficulte',
            'duree' => 'duree',
            'date' => 'date'
        ];
        
        $sortField = $sortFieldMap[$criteria['sort']] ?? 'idFormation';
        $direction = strtoupper($criteria['direction']);
        
        if (!in_array($direction, ['ASC', 'DESC'])) {
            $direction = 'DESC';
        }
        
        $qb->orderBy('f.' . $sortField, $direction);
    } else {
        $qb->orderBy('f.idFormation', 'DESC');
    }

    return $qb;
}
}
