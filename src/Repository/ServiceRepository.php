<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

 /**
     * @param int $idService
     * @return Service|null
     */
    public function findOneByIdContrat(int $idService): ?Service
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.idService = :idService')
            ->setParameter('idService', $idService)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
