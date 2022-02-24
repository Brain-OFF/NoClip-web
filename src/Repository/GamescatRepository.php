<?php

namespace App\Repository;

use App\Entity\Gamescat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gamescat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gamescat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gamescat[]    findAll()
 * @method Gamescat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamescatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gamescat::class);
    }

    // /**
    //  * @return Gamescat[] Returns an array of Gamescat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gamescat
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
