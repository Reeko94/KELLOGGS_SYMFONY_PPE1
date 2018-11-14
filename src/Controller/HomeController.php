<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig_Error_Runtime;

class HomeController extends AbstractController
{

    /**
     * @return Response
     * @Route("/",name="home")
     */
    public function index() : Response
    {
        return $this->render("pages/home.html.twig");
    }


}