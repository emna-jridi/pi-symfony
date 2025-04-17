<?php

namespace App\Repository;

use App\Entity\Contrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contrat>
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrat::class);
    }

 /**
     * @param int $idContrat
     * @return Contrat|null
     */
    public function findOneByIdContrat(int $idContrat): ?Contrat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idContrat = :idContrat')
            ->setParameter('idContrat', $idContrat)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
