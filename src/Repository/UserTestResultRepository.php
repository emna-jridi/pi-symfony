<?php

namespace App\Repository;

use App\Entity\UserTestResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserTestResult>
 *
 * @method UserTestResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTestResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTestResult[]    findAll()
 * @method UserTestResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTestResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTestResult::class);
    }

    // Add custom query methods here if needed

    public function findPassedTestIdsByUser(int $userId): array
    {
        return $this->createQueryBuilder('utr')
            ->select('IDENTITY(utr.test) AS test_id')
            ->where('utr.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getArrayResult();
    }
}
