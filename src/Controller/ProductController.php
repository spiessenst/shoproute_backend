<?php

namespace App\Controller;


use App\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/api/products" , methods={"POST"} ,  name="app_product_add")
     * @return JsonResponse
     */
    public function addProduct(ProductRepository $productRepository){
        // toevoegen van product en departement

        $contents = json_decode( file_get_contents("php://input") );

        $product = $productRepository->addNewProduct($contents->product_name , $contents->department_id);

        return $this->json(['product_id' => $product->getProductId() ,
            'product_name'=> $product->getProductName(),
            'department_id' => $product->getDepartment()->getDepartmentId() ,
            'department_name' => $product->getDepartment()->getDepartmentName()]);

    }

    /**
     * @Route("/api/products" , name="app_product_showall")
     * @return JsonResponse
     */
    public function allProducts(ProductRepository $productRepository){
        //Toon allle producten in Json
        $products_array = [];
        $products = $productRepository->findAll();

        if ( !$products ){
            throw $this->createNotFoundException("No Products Found");
        }
        foreach ( $products as $product){
            $products_array[] = ['product_id' => $product->getProductId() ,
                'product_name' => $product->getProductName() ,
                'department_id' => $product->getDepartment()->getDepartmentId() ,
                'department_name' => $product->getDepartment()->getDepartmentName()];
        }

        return $this->json($products_array);
    }



// niet in gebruik

    /**
     * @Route("/api/products/{productid}" , name="app_product_showone")
     * @return JsonResponse
     * @throws NonUniqueResultException
     */

    public function showOneProduct($productid ,ProductRepository $productRepository)
    {

        $product = $productRepository->showOneProduct($productid);
        return $this->json([['product_id' => $product->getProductId() ,
            'product_name' => $product->getProductName() ,
            'department_id' => $product->getDepartment()->getDepartmentId() ,
            'department_name' => $product->getDepartment()->getDepartmentName()]]);
    }

}