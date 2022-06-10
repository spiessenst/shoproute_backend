<?php

namespace App\Repository;

use App\Entity\ShoppinglistProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShoppinglistProduct>
 *
 * @method ShoppinglistProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppinglistProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppinglistProduct[]    findAll()
 * @method ShoppinglistProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppinglistProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppinglistProduct::class);
    }

    public function add(ShoppinglistProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ShoppinglistProduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @throws Exception
     */
    public function setListProduct($productid , $listid , $checked = 0 , $qty = 1): array
    {

        $sql = "INSERT INTO shoppinglist_product (shoppinglist_id , product_id , qty , checked) VALUES
 (:shoppinglist_id , :product_id , :qty , :checked )";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('shoppinglist_id' ,$listid );
        $statement->bindParam('product_id' ,$productid );
        $statement->bindParam('qty' ,$qty );
        $statement->bindParam('checked' ,$checked );

        $statement->executeQuery();


        return $this->findAll() ;
    }

    /**
     * @throws Exception
     */
    public function deleteListProduct($shoppinglist_id , $product_id): array
    {


        $sql = "DELETE FROM shoppinglist_product WHERE product_id= :product_id AND shoppinglist_id= :shoppinglist_id";

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('shoppinglist_id' ,$shoppinglist_id );
        $statement->bindParam('product_id' ,$product_id );
        $statement->executeQuery();


        return $this->findAll() ;

    }

    /**
     * @throws Exception
     */
    public function updateChecked($shoppinglist_id , $product_id , $checked) : array
    {

            $sql= "UPDATE shoppinglist_product SET checked= :checked WHERE shoppinglist_id = :shoppinglist_id  AND product_id = :product_id";
            $statement = $this->getEntityManager()->getConnection()->prepare($sql);
            $statement->bindParam('shoppinglist_id' ,$shoppinglist_id );
            $statement->bindParam('product_id' ,$product_id );
            $statement->bindParam('checked' ,$checked );
            $statement->executeQuery();


        return $this->findAll() ;
    }


    /**
     * @throws Exception
     */
    public function updateQty($shoppinglist_id , $product_id , $qty) : array
    {

        $sql= "UPDATE shoppinglist_product SET qty= :qty WHERE shoppinglist_id = :shoppinglist_id  AND product_id = :product_id";
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->bindParam('shoppinglist_id' ,$shoppinglist_id );
        $statement->bindParam('product_id' ,$product_id );
        $statement->bindParam('qty' ,$qty );
        $statement->executeQuery();


        return $this->findAll() ;
    }

}
