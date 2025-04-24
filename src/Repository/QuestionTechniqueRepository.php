<?php
namespace App\Repository;

use App\Entity\QuestionTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionTechnique>
 *
 * @method QuestionTechnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionTechnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionTechnique[]    findAll()
 * @method QuestionTechnique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionTechniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionTechnique::class);
    }

    public function findByCategorie(int $categorie): array
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByDifficulte(int $difficulte): array
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.difficulte = :difficulte')
            ->setParameter('difficulte', $difficulte)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategorieAndDifficulte(int $categorie, int $difficulte): array
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.categorie = :categorie')
            ->andWhere('q.difficulte = :difficulte')
            ->setParameter('categorie', $categorie)
            ->setParameter('difficulte', $difficulte)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}