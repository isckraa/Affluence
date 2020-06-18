<?php

namespace App\Repository;

use App\Entity\Recompense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recompense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recompense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recompense[]    findAll()
 * @method Recompense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecompenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recompense::class);
    }

    // /**
    //  * @return Recompense[] Returns an array of Recompense objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recompense
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
