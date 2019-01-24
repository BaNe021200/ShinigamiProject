<?php

namespace App\Repository;

use App\Entity\ShiniPlayerAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @method ShiniPlayerAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiniPlayerAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiniPlayerAccount[]    findAll()
 * @method ShiniPlayerAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiniAccountRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShiniPlayerAccount::class);
    }

    // /**
    //  * @return ShiniAccount[] Returns an array of ShiniAccount objects
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
    public function findOneBySomeField($value): ?ShiniAccount
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



}
