<?php

namespace App\Controller;


use App\Repository\AdminRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /**
     * @Route("/api/user/{email}" , name="app_getuser")
     * @return JsonResponse
     */
    public function getUserForcheck($email , AdminRepository  $adminRepository){

            $user  = $adminRepository->findOneBy(['username' => $email]);

            $user_arr =  ['user_id' => $user->getId()];

            return $this->json( $user_arr);
    }

}