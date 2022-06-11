<?php

namespace App\Repository;

use App\Entity\Store;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Store>
 *
 * @method Store|null find($id, $lockMode = null, $lockVersion = null)
 * @method Store|null findOneBy(array $criteria, array $orderBy = null)
 * @method Store[]    findAll()
 * @method Store[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Store::class);
    }

    public function add(Store $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Store $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**

     * @return Store[] Returns an array of Store objects
     */
    public function findOneStore($value): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.storeId = :val')
            ->setParameter('val', $value)
            ->orderBy('s.storeId', 'ASC')
          //  ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
   }

    /**
     * @throws Exception
     */
    public function UpdateStore($storeId , $storeName , $storeImg , $storeStreet , $storeNr , $StorePostalcode , $storeCity  ){

       $sql= "UPDATE store SET store_name= :storename , store_img = :storeimg , store_street= :storestreet , store_nr= :storenr  , store_postalcode= :storepostal , store_city= :storecity WHERE store_id = :store_id";
       $statement = $this->getEntityManager()->getConnection()->prepare($sql);


       $statement->bindParam('storename' ,$storeName );
        $statement->bindParam('storeimg' ,$storeImg );
       $statement->bindParam('storestreet' ,$storeStreet );
       $statement->bindParam('storenr' ,$storeNr );
       $statement->bindParam('storepostal' ,$StorePostalcode );
       $statement->bindParam('storecity' ,$storeCity );
       $statement->bindParam('store_id' ,$storeId );
       $statement->executeQuery();
   }



}
