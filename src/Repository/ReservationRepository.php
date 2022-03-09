<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }




    public function listReservationByid($id)
    {
        return $this->createQueryBuilder('I')
            ->join('I.coach', 'T')
            ->addSelect('T')
            ->where('T.id=:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }
    public function listReservationByDispo()
    {
        return $this->createQueryBuilder('I')
            ->where('I.dispo = 1')
            ->getQuery()
            ->getResult();
    }
    public function orderByDate()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.tempsstart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function listReservationByidtrier($id)
    {
        return $this->createQueryBuilder('I')
            ->join('I.coach', 'T')
            ->addSelect('T')
            ->where('T.id=:id')
            ->setParameter('id',$id)
            ->orderBy('I.tempsstart', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**
     * @return Query
     */

    public function findAllVisibleQuery(): Query
    {
        return $this->createQueryBuilder('I')
            ->getQuery();

    }
    /**
     * @return Query
     */

    public function findAllVisibleQuerybydispo(): Query
    {
        return $this->createQueryBuilder('I')
            ->where('I.dispo = 1')
           ->getQuery();

    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
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
    public function findOneBySomeField($value): ?Reservation
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
