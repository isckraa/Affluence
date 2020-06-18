<?php

namespace App\Repository;

use App\Entity\CatalogueRecompense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CatalogueRecompense|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogueRecompense|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogueRecompense[]    findAll()
 * @method CatalogueRecompense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogueRecompenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatalogueRecompense::class);
    }

    // /**
    //  * @return CatalogueRecompense[] Returns an array of CatalogueRecompense objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CatalogueRecompense
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
