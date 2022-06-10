<?php

namespace App\Controller;
use App\Entity\Department;
use App\Repository\DepartmentRepository;
use App\Repository\RouteRepository;
use App\Repository\StoreRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class DepartmentController extends AbstractController
{

    /**
     * @Route("/new/departments" , name="app_departments_new")
     * @return Response
     * @throws Exception
     */
    public function newDepartment(EntityManagerInterface $em  , DepartmentRepository $departmentRepository ,RouteRepository $routeRepository , StoreRepository $storeRepository)
    {
        //Kijk of het departement al bestaat in de tabel departments.
        $department_exists = $departmentRepository->findOneBy(['departmentName' => $_POST['departmentname']]);

        // Zoniet maak een nieuw departement aan en maak een ruote aan voor de huidige winkel.
        if (!$department_exists) {
                if($_POST['departmentname'] != ""){
                    $department = new Department();
                    $department->setDepartmentName($_POST['departmentname']);
                    $em->persist($department);
                    $em->flush();
                    $latest_id = $department->getDepartmentId();
                    $sort_order = 0;
                    $routeRepository->addRoutes($latest_id, $sort_order, $_SESSION['storeid']);
                    $this->addFlash('succes' , 'Afdeling toegevoegd');
                }else{
                    $this->addFlash('error' , 'Geen afdeling opgegeven');
                }

        }

        // Als het departement reeds bestaat voeg enkel toe aan de winkel route.
        if($department_exists){
            $sort_order = 0;
            $routeRepository->addRoutes($department_exists->getDepartmentId(), $sort_order, $_SESSION['storeid']);
            $this->addFlash('succes' , 'Afdeling toegevoegd');
        }
        //Haal de route op voor de huidige winkel.
        $routes = $routeRepository->ShopRoute($_SESSION['storeid']);
        $store = $storeRepository->findOneStore( $_SESSION["storeid"] );
        $storeName = $store[0]->getStoreName();


        return $this->render("pages/routesdetail.html.twig"  , [ 'routes' => $routes , 'storename' => $storeName ]);

    }


    /**
     * @Route("/pages/departments" , name="app_departments_showtwig")
     * @return Response
     */
    public function showDepartments(EntityManagerInterface $em){

        // Toon departementen in TWIG (niet in gebruik)
        $repository = $em->getRepository(Department::class);
        $departments =  $repository->findAll();

        return $this->render("pages/departments.html.twig"  , ['departments' =>$departments]);

    }


    /**
     * @Route("/api/departments" , name="app_departments_show")
     * @return JsonResponse
     */
    public function showAllDepartments(DepartmentRepository $departmentrepository)
    {
        //Toon alle departementen in json formaat
        $department_array = [];
        $departments = $departmentrepository->findAll();

        if ( !$departments ){
            throw $this->createNotFoundException("No Departments Found");
        }
        foreach ( $departments as $department){
            $department_array[] = ['department_id'=>$department->getDepartmentId() , 'department_name'=>$department->getDepartmentName() ];
        }

        return $this->json($department_array);
    }
}