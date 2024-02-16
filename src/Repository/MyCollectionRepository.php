<?php

namespace App\Repository;

use App\Entity\MyCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MyCollection>
 *
 * @method MyCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyCollection[]    findAll()
 * @method MyCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MyCollection::class);
    }

//    /**
//     * @return MyCollection[] Returns an array of MyCollection objects
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

//    public function findOneBySomeField($value): ?MyCollection
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findRandomCollectionSql()
{
 $conn = $this->getEntityManager()->getConnection();

 $sql = '
 SELECT *
 FROM my_collection
 ORDER BY RAND()
 LIMIT 1
     ';
 $resultSet = $conn->executeQuery($sql);
 return $resultSet->fetchAssociative();
}

    /*
    public function getRandomMovieDql()
    {
        // step 1 : call EntityMAnager
        $manager = $this->getEntityManager();
        // step 2 : on build SQL query
        $query = $manager->createQuery(
            'SELECT m
            FROM App\Entity\MyCollection AS m
            ORDER BY RAND()'
        )->setMaxResults(1);

        // step 3 : execute the sql query and return the result
        return $query->getResult();
    
    }
    */

}


