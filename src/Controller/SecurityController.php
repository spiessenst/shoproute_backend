<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    /**
     * @Route("/app_json_login", name="app_json_login", methods={"POST"})
     */
    public function login()
    {

        return $this->json([
            "user" => $this->getUser() ? $this->getUser()->getId() : null,
        ]);
    }
}