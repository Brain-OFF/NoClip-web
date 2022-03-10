<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function searchUser($user)
    {
        return $this->createQueryBuilder('u')->where('u.username = :USERNAME')
            ->setParameter('USERNAME',$user)->getQuery()->getResult();
    }
    public function findytoken($token)
    {
        return $this->createQueryBuilder('s')
            ->Where('s.Reset_Token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getSingleResult();
    }
    public function findOneByEmail($mail)
    {
        return $this->createQueryBuilder('s')
            ->Where('s.email = :email')
            ->setParameter('email', $mail)
            ->getQuery()
            ->getSingleResult();
    }
    public function findbyusername($mail)
    {
        try {
            return $this->createQueryBuilder('s')
                ->Where('s.username = :email')
                ->setParameter('email', $mail)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {

            return null;
        } catch (NonUniqueResultException $e) {
        }
    }
    // /**


    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
