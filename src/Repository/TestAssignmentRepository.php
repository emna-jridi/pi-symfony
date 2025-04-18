<?php

namespace App\Repository;

use App\Entity\TestAssignment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class TestAssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestAssignment::class);
    }

    /**
     * Find assignments for a specific user
     */
    public function findByAssignedTo(int $userId): array
    {
        return $this->createQueryBuilder('ta')
            ->andWhere('ta.assignedTo = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('ta.assignedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find assignments that are overdue and not completed
     */
    public function findOverdue(): array
    {
        return $this->createQueryBuilder('ta')
            ->andWhere('ta.dueDate < :now')
            ->andWhere('ta.isCompleted = false')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    /**
     * Count total assignments per userType
     */
    public function countByUserType(string $type): int
    {
        return (int) $this->createQueryBuilder('ta')
            ->select('count(ta.id)')
            ->andWhere('ta.userType = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
