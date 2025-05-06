<?php
namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Formation>
 */
class FormationRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    public function createQueryBuilderWithFilters(string $keyword = '', array $criteria = [], array $filters = []): QueryBuilder {
        $qb = $this->createQueryBuilder('f');
        
        // Recherche par nom uniquement (sans description)
        if (!empty($keyword)) {
            $qb->andWhere('LOWER(f.NomFormation) LIKE LOWER(:keyword)')
               ->setParameter('keyword', '%' . $keyword . '%');
        }
        
        // Filtrage par thème
        if (!empty($filters['theme'])) {
            $qb->andWhere('f.ThemeFormation = :theme')
               ->setParameter('theme', $filters['theme']);
        }
        
        // Filtrage par niveau
        if (!empty($filters['niveau'])) {
            $qb->andWhere('f.niveauDifficulte = :niveau')
               ->setParameter('niveau', $filters['niveau']);
        }
        
        // Tri
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
