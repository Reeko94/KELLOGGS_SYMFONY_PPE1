<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NouveautesController extends AbstractController
{
    use \App\Traits\GetNBArticlesTrait;

    /**
     * @Route("/nouveautes", name="nouveautes")
     * @param ArticlesRepository $articlesRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ArticlesRepository $articlesRepository)
    {
        return $this->render('nouveautes/index.html.twig', [
            'controller_name' => 'NouveautesController',
            'lastfive' => $articlesRepository->getlastfive(),
            'nb' => $this->getNBArticle()
        ]);
    }
}
