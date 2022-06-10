<?php

namespace App\Repository;

use App\Entity\ShopRoute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShopRoute>
 *
 * @method ShopRoute|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShopRoute|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShopRoute[]    findAll()
 * @method ShopRoute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShopRoute::class);
    }

    public function add(ShopRoute $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShopRoute $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return ShopRoute[] Returns an array of ShopRoute objects
     */
    public function ShopRoute($value): array {
        return $this->createQueryBuilder('r')
            ->andWhere('r.store = :val')
            ->setParameter('val', $value)
            ->orderBy('r.sortOrder', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws Exception
     */
    public function UpdateRoute($id , $val){

        $sql= "UPDATE route SET sort_order= :val WHERE route_id = :id";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('val' ,$val );
        $statement->bindParam('id' ,$id );
        $statement->executeQuery();

    }

    /**
     * @throws Exception
     */
    public function addRoutes($latest_id , $sort_order , $storeid){

        $sql = "INSERT INTO route (department_id , sort_order , store_id) VALUES (:department_id , :sort_order , :store_id)";
        $statement =  $this->getEntityManager()->getConnection()->prepare($sql);

        $statement->bindParam('department_id' ,$latest_id );
        $statement->bindParam('sort_order' , $sort_order );
        $statement->bindParam('store_id' ,$storeid );
        $statement->executeQuery();
    }

//    public function findOneBySomeField($value): ?Route
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
