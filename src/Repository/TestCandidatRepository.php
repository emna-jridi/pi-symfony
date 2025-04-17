<?php
namespace App\Repository;

use App\Entity\TestCandidat;
use App\Entity\TestTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestCandidat>
 *
 * @method TestCandidat|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestCandidat|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestCandidat[]    findAll()
 * @method TestCandidat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestCandidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestCandidat::class);
    }

    public function findByTest(TestTechnique $test): array
    {
        return $this->createQueryBuilder('tc')
            ->andWhere('tc.test = :test')
            ->setParameter('test', $test)
            ->orderBy('tc.datePassage', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByEmail(string $email): array
    {
        return $this->createQueryBuilder('tc')
            ->andWhere('tc.emailCandidat = :email')
            ->setParameter('email', $email)
            ->orderBy('tc.datePassage', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTopScores(TestTechnique $test, int $limit = 10): array
    {
        return $this->createQueryBuilder('tc')
            ->andWhere('tc.test = :test')
            ->andWhere('tc.termine = :termine')
            ->setParameter('test', $test)
            ->setParameter('termine', true)
            ->orderBy('tc.score', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getAverageScore(TestTechnique $test): ?float
    {
        $result = $this->createQueryBuilder('tc')
            ->select('AVG(tc.score) as avgScore')
            ->andWhere('tc.test = :test')
            ->andWhere('tc.termine = :termine')
            ->setParameter('test', $test)
            ->setParameter('termine', true)
            ->getQuery()
            ->getSingleScalarResult();
            
        return $result ? (float)$result : null;
    }
}