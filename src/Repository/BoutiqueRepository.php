<?php

namespace App\Repository;

use App\Entity\Boutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;

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


    // latBoutique > latPos - 1m && latBoutique < latPos + 1m && longBoutique > longPos - 1m && longBoutique < longPos + 1m

    /**
     * Collect boutique around a position and by a defined distance.
     * @param $longitude
     * @param $latitude
     * @param int $distance
     * @return Boutique[]
     */
    public function findByGPS($longitude,$latitude, int $distance){
        $longitudeDistance = $distance * 0.00001282;
        $latitudeDistance = $distance * 0.00000901;
        $qb = $this->createQueryBuilder('b');
        return $qb->where('b.Longitude > :longInf')
            ->andWhere('b.Longitude < :longSup')
            ->andWhere('b.Latitude > :latInf')
            ->andWhere('b.Latitude < :latSup')
            ->setParameter('longSup', $longitude + $longitudeDistance)
            ->setParameter('longInf', $longitude - $longitudeDistance)
            ->setParameter('latSup', $latitude + $latitudeDistance)
            ->setParameter('latInf', $latitude - $latitudeDistance)
            ->orderBy('b.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
