<?php

namespace App\Traits;

use App\Entity\Articles;
use App\Entity\Panier;
use App\Repository\PanierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Created by PhpStorm.
 * User: theosikli
 * Date: 2018-12-11
 * Time: 14:25
 */

trait GetNBArticlesTrait
{

    /**
     * @var PanierRepository
     */
    private $panierRepository;

    public function __construct(PanierRepository $panierRepository)
    {
        $this->panierRepository = $panierRepository;
    }

    public function getNBArticle()
    {
        if ($user = $this->getUser()) {
            $panier = $this->panierRepository->checkPanier($user);
            if(count($panier) > 0) {
                if (count((array)json_decode($panier[0]->getArticles())[0]) == 0) {
                    return $this->redirectToRoute('home');
                } else {
                    return count(json_decode($panier[0]->getArticles()));
                }
            }
        }
    }
}