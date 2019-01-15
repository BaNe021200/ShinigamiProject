<?php

namespace App\Repository;

use App\Entity\ShiniOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShiniOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiniOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiniOffer[]    findAll()
 * @method ShiniOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiniOffersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShiniOffer::class);
    }


    public function findVisible()
    {
        return $this->createQueryBuilder('shiniOffers')
            ->where('shiniOffers.shown = 1')
            ->orderBy('shiniOffers.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOnLine()
    {
        return $this->createQueryBuilder('shiniOffers')
            ->where('shiniOffers.onfirstpage = 1')
            ->orderBy('shiniOffers.id', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }


    // /**
    //  * @return ShiniOffer[] Returns an array of ShiniOffer objects
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
    public function findOneBySomeField($value): ?ShiniOffer
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
