<?php

namespace App\Repository;

use App\Entity\ShiniCenter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShiniCenter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiniCenter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiniCenter[]    findAll()
 * @method ShiniCenter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiniCenterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShiniCenter::class);
    }

    // /**
    //  * @return ShiniCenter[] Returns an array of ShiniCenter objects
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
    public function findOneBySomeField($value): ?ShiniCenter
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findCenterbyStaffId($staffId):?ShiniCenter
    {
        return $this->createQueryBuilder('center')
            ->select('center.code')
            ->where('center.staff = :staff')
            ->setParameter('staff', $staffId)
            ->getQuery()
            ->getResult();
    }

}
