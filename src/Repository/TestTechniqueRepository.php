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
}
