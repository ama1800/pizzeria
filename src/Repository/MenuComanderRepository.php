<?php

namespace App\Repository;

use App\Entity\MenuComander;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MenuComander|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuComander|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuComander[]    findAll()
 * @method MenuComander[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuComanderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuComander::class);
    }

    // /**
    //  * @return MenuComander[] Returns an array of MenuComander objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuComander
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
