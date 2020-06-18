<?php

namespace App\Repository;

use App\Entity\InfoFileAttente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InfoFileAttente|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfoFileAttente|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfoFileAttente[]    findAll()
 * @method InfoFileAttente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfoFileAttenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfoFileAttente::class);
    }

    // /**
    //  * @return InfoFileAttente[] Returns an array of InfoFileAttente objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InfoFileAttente
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
