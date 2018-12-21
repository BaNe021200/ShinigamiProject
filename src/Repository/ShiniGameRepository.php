<?php

namespace App\Repository;

use App\Entity\ShiniGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShiniGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiniGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiniGame[]    findAll()
 * @method ShiniGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiniGameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShiniGame::class);
    }

    // /**
    //  * @return ShiniGame[] Returns an array of ShiniGame objects
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
    public function findOneBySomeField($value): ?ShiniGame
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
