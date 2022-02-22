<?php

namespace App\Repository;

use App\Entity\inscriptionT;
use App\Entity\Tournoi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method inscriptionT|null find($id, $lockMode = null, $lockVersion = null)
 * @method inscriptionT|null findOneBy(array $criteria, array $orderBy = null)
 * @method inscriptionT[]    findAll()
 * @method inscriptionT[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionTRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, inscriptionT::class);
    }

    // /**
    //  * @return inscriptionT[] Returns an array of inscriptionT objects
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
    public function findOneBySomeField($value): ?inscriptionT
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function listInscriptionByTournoi($id)
    {
        return $this->createQueryBuilder('I')
            ->join('I.tournoi', 'T')
            ->addSelect('T')
            ->where('T.id=:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

}
