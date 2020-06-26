<?php

namespace App\Repository;

use App\Entity\Boutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Boutique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boutique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boutique[]    findAll()
 * @method Boutique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoutiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boutique::class);
    }

    // /**
    //  * @return Boutique[] Returns an array of Boutique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Boutique
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $value
     * @return Boutique[]
     */
    public function findByApproximatifNom($value) {
        $qb = $this->createQueryBuilder('b');
        return $qb->where($qb->expr()->like('b.nom', ':val'))
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $value
     * @return Boutique[]
     */
    public function findByApproximatifVille($value) {
        $qb = $this->createQueryBuilder('b');
        return $qb->where($qb->expr()->like('b.ville', ':val'))
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $value
     * @return Boutique[]
     */
    public function findByApproximatifCodePostal($value) {
        $qb = $this->createQueryBuilder('b');
        return $qb->where($qb->expr()->like('b.codePostal', ':val'))
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
