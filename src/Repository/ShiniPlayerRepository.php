<?php

namespace App\Repository;

use App\Entity\ShiniPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method ShiniPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiniPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiniPlayer[]    findAll()
 * @method ShiniPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiniPlayerRepository extends ServiceEntityRepository implements UserProviderInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShiniPlayer::class);
    }

    public function findAllQuery():Query
    {
       return $this->createQueryBuilder('players')
           ->select('players')
           ->getQuery();
    }


    public function findPassword($id)
    {
        return $this->createQueryBuilder('s')
            ->select('s.password')
            ->where('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findEmailconfirm($id)
    {
       return $this->createQueryBuilder('player')
           ->select('player.confirmation_token')
           ->where('player.id = :id')
           ->setParameter('id',$id)
           ->getQuery()
           ->getSingleScalarResult();
    }


    // /**
    //  * @return ShiniPlayer[] Returns an array of ShiniPlayer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ShiniPlayer
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->createQueryBuilder('s')
            ->where('s.email = :username AND s.confirmed_at IS NOT NULL')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if(!$user) {
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    /**
     * Refreshes the user.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     * @return UserInterface
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof ShiniPlayer) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === ShiniPlayer::class;
    }
}
