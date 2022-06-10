<?php

namespace App\Controller;
use App\Entity\ShopRoute;
use App\Entity\Store;
use App\Repository\RouteRepository;
use App\Repository\StoreRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class RouteController extends AbstractController
{

    /**
     * @Route("/pages/routes/detail" , name="app_routes_showdetailtwig")
     * @return Response
     */
    public function showRoutesFind( RouteRepository  $routeRepository , StoreRepository $storeRepository){

        //detail route

        $_SESSION["storeid"] = $_POST['stores'] ;
         $routes = $routeRepository->ShopRoute($_SESSION["storeid"]);

        $store = $storeRepository->findOneStore($_SESSION["storeid"]);
        $storeName = $store[0]->getStoreName();

        return $this->render("pages/routesdetail.html.twig"  , [ 'routes' => $routes , 'storename' => $storeName ]);
    }

    /**
     * @Route("/pages/routes" , name="app_routes_showtwiginit")
     * @return Response
     */
    public function showRoutes( EntityManagerInterface $em){

            //Keuze winkel voor routes
             $repository_store = $em->getRepository(Store::class);
             $stores =  $repository_store->findAll();

             return $this->render("pages/routes.html.twig"  , [ 'stores' => $stores]);

    }

    /**
     * @Route("/pages/routes/update", name="update")
     * @throws Exception
     */
    /**
     * @Route("/pages/routes/update", name="update")
     * @throws Exception
     */
    public function UpdateRoute(RouteRepository $routeRepository , StoreRepository $storeRepository  ): Response
    {

        $array = $_POST;
        $i = 1;
        foreach ($array as $key => $value) {
            $routeRepository->sortRoute($i ,$key);
            $i++;
        }

        $routes = $routeRepository->ShopRoute($_SESSION['storeid']);
        $store = $storeRepository->findOneStore($_SESSION["storeid"]);
        $storeName = $store[0]->getStoreName();
        $this->addFlash('succes' , 'Volgorde gewijzigd');


        return $this->render("pages/routesdetail.html.twig"  , [ 'routes' => $routes , 'storename' => $storeName ]);
    }

    /**
     * @Route("/route/{id}/delete", name="delete_route")
     */
    public function removeRoute(ShopRoute $route , RouteRepository $routeRepository , StoreRepository  $storeRepository)
    {
        //route verwijderen voor de huidige winkel
        $routeRepository->remove($route , true);

       // $routes = $routeRepository->ShopRoute( $_SESSION["storeid"] );
      //  $store = $storeRepository->findOneStore( $_SESSION["storeid"] );
     //   $storeName = $store[0]->getStoreName();

        $this->addFlash('error' , 'Afdeling verwijderd');

     //   return $this->render("pages/routesdetail.html.twig"  , [ 'routes' => $routes , 'storename' => $storeName ]);

    }


}