<?php

namespace App\Repository;

use App\Entity\FileAttente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FileAttente|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileAttente|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileAttente[]    findAll()
 * @method FileAttente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileAttenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileAttente::class);
    }

    // /**
    //  * @return FileAttente[] Returns an array of FileAttente objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FileAttente
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
