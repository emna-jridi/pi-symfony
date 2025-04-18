<?php

namespace App\Repository;

use App\Entity\ContratEmploye;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContratEmploye>
 */
class ContratEmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContratEmploye::class);
    }

 /**
     * @param int $idContratEmp
     * @return ContratEmploye|null
     */
    public function findOneByIdContratEmp(int $idContratEmp): ?ContratEmploye
    {
        return $this->createQueryBuilder('co')
            ->andWhere('co.idContratEmp = :idContratEmp')
            ->setParameter('idContratEmp', $idContratEmp)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
