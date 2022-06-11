<?php

namespace App\Controller;

use App\Entity\Store;
use App\Repository\RouteRepository;
use App\Repository\StoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    /**
     * @Route("/store/{id}/delete", name="delete")
     */
    public function removeStore(Store $store , StoreRepository $StoreRepository , EntityManagerInterface $em , RouteRepository $routeRepository)
    {

       $route_exists =  $routeRepository->findBy(['store' => $store->getStoreId()]);
        if($route_exists == []){
        //verwijder winkel
        $StoreRepository->remove($store , true);

        $this->addFlash('error' , 'Winkel verwijderd');
        }
        else{
            $this->addFlash('error' , 'Deze winkel bevat routes verwijder eerst deze');
        }
        return $this->addStores($em);

    }


    /**
     * @Route("/pages/storedetail/{id}" , name="store_detail")
     * @return Response
     */
    public function detailStores($id , EntityManagerInterface $em){



        $repository = $em->getRepository(Store::class);
        $stores =  $repository->findOneBy(['storeId' => $id]);


        return $this->render("pages/storedetail.html.twig"  , ['Store' => $stores]);

    }

    /**
     * @Route("/pages/addstores" , name="app_stores_addnew")
     * @return Response
     */
    public function addStores(EntityManagerInterface $em){
        //winkel lijst twig
        $repository = $em->getRepository(Store::class);
        $stores =  $repository->findAll();


        return $this->render("pages/stores.html.twig"  , ['Stores' => $stores]);


    }
    /**
     * @Route("/update/store", methods={"POST"}, name="app_stores_update")
     * @return Response
     */
    public function updateStore(  StoreRepository $storeRepository , EntityManagerInterface $em )

    {

        $storeRepository->UpdateStore($_POST['storeid'] , $_POST['storename'] , $_FILES['storeimage']['name'] ,
            $_POST['storestreet'] , $_POST['storenr'] , $_POST['storepostalcode'] ,$_POST['storecity']);


        $uploaddir = $_SERVER['DOCUMENT_ROOT']. "/fs_thomass/eindwerk/images/";
        //$uploaddir = $_SERVER['DOCUMENT_ROOT']. "/images/";

        $uploadfile = $uploaddir . basename($_FILES['storeimage']['name']);


        if (move_uploaded_file($_FILES['storeimage']['tmp_name'], $uploadfile)) {
            $this->addFlash('succes' , 'Winkel aangepast');


        } else {
            $this->addFlash('error' , 'Vul alle velden correct in');
        }

        return $this->addStores($em);
    }

    /**
     * @Route("/new/store", methods={"POST"}, name="app_stores_new")
     * @return Response
     */
    public function newStore(   EntityManagerInterface $em )

    {
        //Nieuwe winkel toevoegen
        $store = new Store();
        $store->setStoreName($_POST['storename']);
        $store->setStoreStreet($_POST['storestreet']);
        $store->setStoreNr($_POST['storenr']);
        $store->setStorePostalcode($_POST['storepostalcode']);
        $store->setStoreCity($_POST['storecity']);
        $store->setStoreImg($_FILES['storeimage']['name']);


       $uploaddir = $_SERVER['DOCUMENT_ROOT']. "/fs_thomass/eindwerk/images/";
      //  $uploaddir = $_SERVER['DOCUMENT_ROOT']. "/images/";

        $uploadfile = $uploaddir . basename($_FILES['storeimage']['name']);


        if (move_uploaded_file($_FILES['storeimage']['tmp_name'], $uploadfile)) {
            $this->addFlash('succes' , 'Winkel toegevoegd');
            $em->persist($store);
            $em->flush();

        } else {
            $this->addFlash('error' , 'Vul alle velden correct in');
        }

        return $this->addStores($em);
    }

    /**
     * @Route("/api/stores/{storeid}" , name="app_stores_showone")
     * @return JsonResponse
     */
    public function showOneStores($storeid ,StoreRepository $StoreRepository)
    {
        //een winkel selecteren json
       $store= $StoreRepository->findOneStore($storeid);
       return $this->json([['store_id'=>$store[0]->getStoreId() ,
           'store_name'=>$store[0]->getStoreName() ,
           'store_img'=>$store[0]->getStoreImg()]]);
    }


    /**
     * @Route("/api/stores" , name="app_stores_show")
     * @return JsonResponse
     */
    public function showAllStores(StoreRepository $StoreRepository)
    {

        //Alle winkels json
        $store_array = [];
        $stores = $StoreRepository->findAll();

        if ( !$stores ){
            throw $this->createNotFoundException("No Stores Found");
        }
                foreach ( $stores as $store){
                   $store_array[] = ['store_id'=>$store->getStoreId() ,
                       'store_name'=>$store->getStoreName() ,
                       'store_img'=>$store->getStoreImg()];
                }

            return $this->json($store_array);
    }




}