<?php
namespace App\Repository;

use App\Entity\TestTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestTechnique>
 *
 * @method TestTechnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestTechnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestTechnique[]    findAll()
 * @method TestTechnique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestTechniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestTechnique::class);
    }

    public function findActive(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findWithQuestions(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.questions', 'q')
            ->addSelect('q')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRecent(int $limit = 5): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    public function findFiltered(string $search = '', string $duration = '')
{
    $qb = $this->createQueryBuilder('t')
        ->leftJoin('t.questions', 'q')
        ->groupBy('t.id');
    
    if (!empty($search)) {
        $qb->andWhere('t.titre LIKE :search')
           ->setParameter('search', '%' . $search . '%');
    }
    
    if (!empty($duration)) {
        if ($duration === '15') {
            $qb->andWhere('t.dureeMinutes <= 15');
        } elseif ($duration === '30') {
            $qb->andWhere('t.dureeMinutes <= 30');
        } elseif ($duration === '60') {
            $qb->andWhere('t.dureeMinutes <= 60');
        } elseif ($duration === '61') {
            $qb->andWhere('t.dureeMinutes > 60');
        }
    }
    
    return $qb->getQuery()->getResult();
}
}
