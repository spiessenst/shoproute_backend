<?php

namespace App\Controller;


use App\Repository\ShoppinglistProductRepository;
use App\Repository\ShoppinglistRepository;
use Doctrine\DBAL\Exception;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ListController extends AbstractController

{
    /**
     * @Route("/api/lists" , methods={"PATCH"} ,name="Patch_list")
     * @return Response
     * @throws Exception
     */
    public function updateList(ShoppinglistProductRepository $shoppinglistProductRepository){
            //update checked or qty

        $contents = json_decode(file_get_contents("php://input"));
        $listproduct_array = [];
        if (property_exists($contents, "checked") )  {

            $lists = $shoppinglistProductRepository->updateChecked($contents->shoppinglist_id , $contents->product_id  , $contents->checked);

            foreach ($lists as $listproduct) {
                $listproduct_array[] = ["id" => $listproduct->getId(),
                    "product_id" => $listproduct->getProduct()->getProductId(),
                    "shoppinglist_id" => $listproduct->getShoppinglist()->getShoppinglistId(),
                    "checked" => $listproduct->isChecked(),
                    "qty" => $listproduct->getQty()];
            }

            return $this->json($listproduct_array);

        }

        if (property_exists($contents, "qty") )  {

            $lists = $shoppinglistProductRepository->updateQty($contents->shoppinglist_id , $contents->product_id  , $contents->qty);

            foreach ($lists as $listproduct) {
                $listproduct_array[] = ["id" => $listproduct->getId(),
                    "product_id" => $listproduct->getProduct()->getProductId(),
                    "shoppinglist_id" => $listproduct->getShoppinglist()->getShoppinglistId(),
                    "checked" => $listproduct->isChecked(),
                    "qty" => $listproduct->getQty()];
            }

            return $this->json($listproduct_array);

        }


    }


    /**
     * @Route("/api/lists" , methods={"DELETE"} ,name="DELETE_list")
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteList( ShoppinglistRepository  $shoppinglistRepository , ShoppinglistProductRepository $shoppinglistProductRepository)
    {

        //delete List and all products on that list.

        $contents = json_decode(file_get_contents("php://input"));

        if (property_exists($contents, "shoppinglist_id") && !property_exists($contents, "product_id"))  {

            $lists_array = [];
            $lists = $shoppinglistRepository->deleteList($contents->shoppinglist_id);


            if (!$lists) {
                throw $this->createNotFoundException("Error");
            }

            foreach ($lists as $list) {
                $lists_array[] = ['shopppinglist_id' => $list->getShoppinglistId(),
                    'shoppinglist_create_date' => $list->getShoppinglistCreateDateString(),
                    'shoppinglist_name' => $list->getShoppinglistName()];
            }

            return $this->json($lists_array);

        }

        // delete product from list

        if ( property_exists($contents, "shoppinglist_id") && property_exists($contents, "product_id")) {

            $listproduct_array = [];
            $lists = $shoppinglistProductRepository->deleteListProduct($contents->shoppinglist_id, $contents->product_id);

            foreach ($lists as $listproduct) {
                $listproduct_array[] = ["id" => $listproduct->getId(),
                    "product_id" => $listproduct->getProduct()->getProductId(),
                    "shoppinglist_id" => $listproduct->getShoppinglist()->getShoppinglistId(),
                    "checked" => $listproduct->isChecked(),
                    "qty" => $listproduct->getQty()];
            }

            return $this->json($listproduct_array);
        }

    }

    /**
     * @Route("/api/lists" , methods={"POST"} ,name="new_list")
     * @return JsonResponse
     * @throws Exception
     */
    public function newLists(ShoppinglistRepository $shoppinglistRepository , ShoppinglistProductRepository  $shoppinglistProductRepository){

            // Make new List

            $contents = json_decode( file_get_contents("php://input") );


            if (property_exists($contents , "shoppinglist_name"))
            {
                $newListName = $contents->shoppinglist_name;
                $newList = $shoppinglistRepository->setList($newListName);

                return $this->json(['shopppinglist_id' => $newList->getShoppinglistId() ,
                                    'shoppinglist_create_date'=> $newList->getShoppinglistCreateDateString() ,
                                    'shopppinglist_name' => $newList->getShoppinglistName() ]);
            }

            if(property_exists($contents , "shoppinglist_id") && property_exists($contents , "product_id"))
            {

                // Add new product on list

                $newlistproduct_array =[];
                $newlistproduct = $shoppinglistProductRepository->setListProduct($contents->product_id , $contents->shoppinglist_id);

                foreach ( $newlistproduct as $product){
                    $newlistproduct_array[] = ['id' =>$product->getId() ,
                                                'product_id' =>$product->getProduct()->getProductId() ,
                                                "shoppinglist_id" => $product->getShoppinglist()->getShoppinglistId(),
                                                "checked"=> $product->isChecked() ,
                                                "qty"=> $product->getQty()];
                }

              return $this->json($newlistproduct_array);
            }

    }

    /**
     * @Route("/api/lists" , name="app_alllists")
     * @return JsonResponse
     */
    public function giveAllLists( ShoppinglistRepository $shoppinglistRepository){

        // Toon alle lijsten in json
        $lists_array = [];
        $lists = $shoppinglistRepository->allStoresByDate();

        if ( !$lists){
            throw $this->createNotFoundException("No Lists Found");
        }
        foreach ( $lists as $list){
            $lists_array[] = ['shopppinglist_id'=>$list->getShoppinglistId() ,
                'shoppinglist_create_date'=>$list->getShoppinglistCreateDateString() ,
                'shoppinglist_name'=>$list->getShoppinglistName()];
        }

        return $this->json($lists_array);

    }



    /**
     * @Route("/api/lists/{shoppinglistid}/{storeid}" , name="app_lists")
     * @return JsonResponse
     */
    public function giveLists($shoppinglistid ,$storeid , ShoppinglistRepository $shoppinglistRepository){

        //Toon de juiste lijst voor de juiste winkel

        $lists = $shoppinglistRepository->giveListForStore($shoppinglistid , $storeid);
        return $this->json($lists);

    }





}