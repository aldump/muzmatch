<?php

namespace App\Repository;

use App\Entity\Swipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Swipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Swipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Swipe[]    findAll()
 * @method Swipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SwipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Swipe::class);
    }

    // /**
    //  * @return Swipe[] Returns an array of Swipe objects
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
    public function findOneBySomeField($value): ?Swipe
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
