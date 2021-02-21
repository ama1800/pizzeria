<?php

namespace App\Repository;

use App\Entity\CommandeProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommandeProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeProducts[]    findAll()
 * @method CommandeProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeProducts::class);
    }

    // /**
    //  * @return CommandeProducts[] Returns an array of CommandeProducts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommandeProducts
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
