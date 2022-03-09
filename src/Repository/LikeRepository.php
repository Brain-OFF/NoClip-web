<?php

namespace App\Repository;

use App\Entity\Like;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Like|null find($id, $lockMode = null, $lockVersion = null)
 * @method Like|null findOneBy(array $criteria, array $orderBy = null)
 * @method Like[]    findAll()
 * @method Like[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }
    public function search_like($user,$id)
    {
        try {
            return $this->createQueryBuilder('L')->where('L.User = :User')->andWhere('L.Article = :Article')
                ->setParameter('User', $user)->setParameter('Article', $id)->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
        }
    }
    public function likeratio($article)
    {

        return $this->createQueryBuilder('L')->where('L.Article = :Article')->andWhere('L.Status = :L')
            ->setParameter('Article', $article)->setParameter('L', "L")->getQuery()->getResult()->count();

    }
    public function dislikeratio($article)
    {

        return $this->createQueryBuilder('L')->where('L.Article = :Article')->andWhere('L.Status = :D')
            ->setParameter('Article', $article)->setParameter('D', "D")->getQuery()->getResult()->count();

    }

    // /**
    //  * @return Like[] Returns an array of Like objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Like
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
