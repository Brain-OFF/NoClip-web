<?php

namespace App\Repository;

use App\Entity\Tournoi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Tournoi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournoi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournoi[]    findAll()
 * @method Tournoi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournoiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournoi::class);
    }

    // /**
    //  * @return Tournoi[] Returns an array of Tournoi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tournoi
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function orderByDate()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.Date', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function searchCathegorie($nom)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
            ->getQuery()
            ->execute();
    }

}
