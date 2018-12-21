<?php

namespace App\Repository;

use App\Entity\ShiniPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShiniPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiniPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiniPlayer[]    findAll()
 * @method ShiniPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiniPlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShiniPlayer::class);
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
}
