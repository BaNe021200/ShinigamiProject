<?php

namespace App\Repository;

use App\Entity\ShiniCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShiniCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiniCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiniCard[]    findAll()
 * @method ShiniCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiniCardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShiniCard::class);
    }

    // /**
    //  * @return ShiniCard[] Returns an array of ShiniCard objects
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
    public function findOneBySomeField($value): ?ShiniCard
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
