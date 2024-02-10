<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
public function  findAllCategoriesChild()
   {
    $conn = $this->getEntityManager()->getConnection();

    $sql = '
    SELECT c.id, c.name , c.image
    FROM category_category As cc
    INNER JOIN category AS c ON cc.category_target = c.id
    ';

    $resultSet = $conn->executeQuery($sql);
    return $resultSet->fetchAllAssociative();
   }

   public function  findAllCategoriesParent()
   {
    $conn = $this->getEntityManager()->getConnection();

    $sql = '
    SELECT DISTINCT c.id, c.name, c.image
    FROM category_category As cc
    INNER JOIN category AS c ON cc.category_source = c.id
    ';

    $resultSet = $conn->executeQuery($sql);
    return $resultSet->fetchAllAssociative();
   }

   public function  findAllCategoriesRelation()
   {
    $conn = $this->getEntityManager()->getConnection();

    $sql = '
    SELECT c1.id, c1.name AS category_source, c2.name AS category_target 
    FROM category_category AS cc 
    INNER JOIN category AS c1 ON cc.category_source = c1.id 
    INNER JOIN category AS c2 ON cc.category_target = c2.id;
    ';

    $resultSet = $conn->executeQuery($sql);
    return $resultSet->fetchAllAssociative();
   }

//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
