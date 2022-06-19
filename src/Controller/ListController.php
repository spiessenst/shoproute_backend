<?php

namespace App\Controller;


use App\Entity\Shoppinglist;
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
    public function updateList(ShoppinglistProductRepository $shoppinglistProductRepository , ShoppinglistRepository $shoppinglistRepository){
            //update checked of qty

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

        if(property_exists($contents , "shoppinglist_name")) {

            $list = $shoppinglistRepository->updateListName($contents->shoppinglist_id, $contents->shoppinglist_name);
        }

       return $this->json([['shoppinglist_id'=>$list->getShoppinglistId() ,
           'shoppinglist_create_date'=>$list->getShoppinglistCreateDateString() ,
           'shoppinglist_name'=>$list->getShoppinglistName(),
       'user_id'=>$list->getUserId()]]);
    }


    /**
     * @Route("/api/lists" , methods={"DELETE"} ,name="DELETE_list")
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteList( ShoppinglistRepository  $shoppinglistRepository , ShoppinglistProductRepository $shoppinglistProductRepository)
    {

        //lijst en alle producten op die lijst verwijderen

        $contents = json_decode(file_get_contents("php://input"));

        if (property_exists($contents, "shoppinglist_id") && !property_exists($contents, "product_id"))  {

            $lists_array = [];
            $lists = $shoppinglistRepository->deleteList($contents->shoppinglist_id);


            if (!$lists) {
                return $this->json($lists_array);
            }

            foreach ($lists as $list) {
                $lists_array[] = ['shoppinglist_id' => $list->getShoppinglistId(),
                    'shoppinglist_create_date' => $list->getShoppinglistCreateDateString(),
                    'shoppinglist_name' => $list->getShoppinglistName(),
                    'user_id' =>$list->getUserId()];
            }

            return $this->json($lists_array);

        }

        // lijst verwijderen

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

            // nieuwe lijst maken

            $contents = json_decode( file_get_contents("php://input") );


            if (property_exists($contents , "shoppinglist_name"))
            {
                $newListName = $contents->shoppinglist_name;
                $userid = $contents->user_id;
                $newList = $shoppinglistRepository->setList($newListName,$userid );

                return $this->json(['shoppinglist_id' => $newList->getShoppinglistId() ,
                                    'shoppinglist_create_date'=> $newList->getShoppinglistCreateDateString() ,
                                    'shoppinglist_name' => $newList->getShoppinglistName(),
                                    'user_id' =>$newList->getUserId()]);
            }

            if(property_exists($contents , "shoppinglist_id") && property_exists($contents , "product_id"))
            {

                // Nieuw product aan lijst toevoegen.

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
     * @Route("/api/lists/{id}" , name="app_alllists")
     * @return JsonResponse
     */
    public function giveAllLists( $id = null ,ShoppinglistRepository $shoppinglistRepository){

        // Toon alle lijsten in json
        $lists_array = [];
        $lists = $shoppinglistRepository->allListsByDate($id);



        foreach ( $lists as $list){
            $lists_array[] = [

                'shoppinglist_id'=>$list->getShoppinglistId() ,
                'shoppinglist_create_date'=>$list->getShoppinglistCreateDateString() ,
                'shoppinglist_name'=>$list->getShoppinglistName(),
                'user_id'=>$list->getUserId()];
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


    /**
     * @Route("/api/list/{shoppinglistid}" , name="app_lists_nostore")
     * @return JsonResponse
     */
    public function giveAllStoresLists($shoppinglistid  , ShoppinglistRepository $shoppinglistRepository){

        //Toon de juiste lijst onafhankelijk van winkel

        $lists = $shoppinglistRepository->giveListforAllStores($shoppinglistid );


        return $this->json($lists);


    }


}