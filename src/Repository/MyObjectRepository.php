<?php

namespace App\Repository;

use App\Entity\MyObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MyObject>
 *
 * @method MyObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyObject[]    findAll()
 * @method MyObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MyObject::class);
    }

//    /**
//     * @return MyObject[] Returns an array of MyObject objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MyObject
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
