<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\ComposeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\GetNBArticlesService;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    use \App\Traits\GetNBArticlesTrait;

    /**
     * @return Response
     * @Route("/",name="home")
     */
    public function index() : Response
    {
        return $this->render("pages/home.html.twig",['nb' => $this->getNBArticle()]);
    }


}