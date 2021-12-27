<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function index(): Response
    {
        //return $this->json([
        //    'message' => 'Welcome to your new controller!',
        //    'path' => 'src/Controller/MainController.php',
        //]);
        $welcome = 'Welcome to Microserice Portofolio Publikasi Version 1.0 ';
        $date = date('Y-m-d H:i:s');
        $response = new Response($welcome . '</br>' . $date . '.', Response::HTTP_OK);
        return $response;
    }
}
