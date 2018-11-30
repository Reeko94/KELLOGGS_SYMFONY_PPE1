<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\ComposeRepository;
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
    public function index(ComposeRepository $c) : Response
    {
        return $this->render("pages/home.html.twig");
    }

    /**
     * @Route("/show/{id}", methods={"GET"})
     * @param Utilisateur $user
     * @return Response
     */
    public function show(Utilisateur $user)
    {
        return $this->render("pages/show.html.twig", ['user' => $user]);
    }


}