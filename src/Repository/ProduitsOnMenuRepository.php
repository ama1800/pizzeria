<?php

namespace App\Repository;

use App\Entity\ProduitsOnMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProduitsOnMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitsOnMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitsOnMenu[]    findAll()
 * @method ProduitsOnMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsOnMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitsOnMenu::class);
    }

    // /**
    //  * @return ProduitsOnMenu[] Returns an array of ProduitsOnMenu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProduitsOnMenu
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
