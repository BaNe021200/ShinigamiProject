<?php

namespace App\Repository;

use App\Entity\ShiniStaff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShiniStaff|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiniStaff|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiniStaff[]    findAll()
 * @method ShiniStaff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiniStaffRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShiniStaff::class);
    }

    public function findStaffWithCenter()
    {
        return $this->createQueryBuilder('staff')
            ->leftJoin('staff.center', 'center')
            ->addSelect('center')
            ->getQuery()->getResult();

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

    // /**
    //  * @return ShiniStaff[] Returns an array of ShiniStaff objects
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
    public function findOneBySomeField($value): ?ShiniStaff
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
