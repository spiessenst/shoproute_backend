<?php

namespace App\Repository;

use App\Entity\Shoppinglist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;



/**
 * @extends ServiceEntityRepository<Shoppinglist>
 *
 * @method Shoppinglist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shoppinglist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shoppinglist[]    findAll()
 * @method Shoppinglist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppinglistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shoppinglist::class);
    }

    public function add(Shoppinglist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Shoppinglist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function giveListforAllStores($shoppinglistid) :array {

        $sql = "SELECT * FROM shoppinglist_product where shoppinglist_id = :shoppingid";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('shoppingid' ,$shoppinglistid );


        return $statement->executeQuery()->fetchAllAssociative();

    }

    public function giveListForStore($shoppinglistid  , $storeid  ) : array
    {

        $sql = "SELECT  d.department_id , d.department_name , product.product_id , product_name , qty , checked from product
INNER JOIN shoppinglist_product sp on product.product_id = sp.product_id
INNER JOIN department d on product.department_id = d.department_id
INNER JOIN route r on d.department_id = r.department_id
WHERE sp.shoppinglist_id like :shoppingid AND r.store_id like :storeid
order by r.sort_order;";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('shoppingid' ,$shoppinglistid );
        $statement->bindParam('storeid' ,$storeid );

        return $statement->executeQuery()->fetchAllAssociative();


    }

   /**
    * @return Shoppinglist[] Returns an array of Shoppinglist objects
     */
   public function  allListsByDate(): array
   {
       return $this->createQueryBuilder('s')
            ->orderBy('s.shoppinglistCreateDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
       ;
    }

    /**
     * @throws Exception
     */
    public function setList($listname){

        $sql = "INSERT INTO shoppinglist SET shoppinglist_name= :shoppinglist_name";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('shoppinglist_name' ,$listname );
        $statement->executeQuery();

        $latestId = $this->getEntityManager()->getConnection()->lastInsertId();

        return $this->findOneBy(['shoppinglistId' => $latestId]) ;
    }

    /**
     * @throws Exception
     */
    public function deleteList($shoppinglist_id){


       $sql = "DELETE FROM shoppinglist_product WHERE shoppinglist_id= :shoppinglist_id";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('shoppinglist_id' ,$shoppinglist_id );
        $statement->executeQuery();

        $sql = "DELETE FROM shoppinglist WHERE shoppinglist_id= :shoppinglist_id";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('shoppinglist_id' ,$shoppinglist_id );
        $statement->executeQuery();

        return $this->findAll() ;

    }

    /**
     * @throws Exception
     */
    public function updateListName($shoppinglist_id , $shoppinglist_name){

        $sql = "UPDATE shoppinglist SET shoppinglist_name= :shoppinglist_name WHERE shoppinglist_id = :shoppinglist_id";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('shoppinglist_id' ,$shoppinglist_id );
        $statement->bindParam('shoppinglist_name' ,$shoppinglist_name );
        $statement->executeQuery();


        return $this->findOneBy(['shoppinglistId' => $shoppinglist_id]) ;

    }



}
