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

     /**
     * Collect info of waiting queue for a certain boutique and hour.
     * @return InfoFileAttente[]
     */
    public function findByQueueDate($fileId){
        $today = date("Y-m-d");
        $hour = date("H:i:s");

        $timestampMinus = strtotime($hour) - 60*60*2;
        $hourMinus = date("H:i:s", $timestampMinus);

        $entityManager = $this->getEntityManager();

        // $query = $entityManager->createQuery(
        //     'SELECT ifa.heure_entree
        //     FROM App\Entity\InfoFileAttente ifa
        //     WHERE ifa.heure_entree BETWEEN "15:00:00" AND "16:00:00"'
        // );
        // return $query->getResult(); 

        $qb = $this->createQueryBuilder('ifa');
        return $qb->where('ifa.dayDate = :date')
            ->andWhere('ifa.fileAttente = :fileId')
            ->andWhere('ifa.heure_entree between :hourMinus and :hour')
            ->setParameter('date', $today)
            ->setParameter('fileId', $fileId)
            ->setParameter('hourMinus', $hourMinus)
            ->setParameter('hour', $hour)
            ->orderBy('ifa.id', 'ASC')
            ->getQuery()
            ->getResult(); 
        
    }

    /**
     * Collect info of waiting queue for a certain boutique, hour and user.
     * @return InfoFileAttente[]
     */
    public function findByUser($userId, $fileId){
        $today = date("Y-m-d");
        $hour = date("H:i:s");

        $timestampMinus = strtotime($hour) - 60*60;
        $hourMinus = date("H:i:s", $timestampMinus);

        $entityManager = $this->getEntityManager();

        $qb = $this->createQueryBuilder('ifa');
        return $qb->where('ifa.dayDate = :date')
            ->andWhere('ifa.fileAttente = :fileId')
            ->andWhere('ifa.user = :userId')
            ->andWhere('ifa.heure_entree between :hourMinus and :hour')
            ->setParameter('date', $today)
            ->setParameter('fileId', $fileId)
            ->setParameter('userId', $userId)
            ->setParameter('hourMinus', $hourMinus)
            ->setParameter('hour', $hour)
            ->orderBy('ifa.id', 'ASC')
            ->getQuery()
            ->getResult(); 
    }
}
