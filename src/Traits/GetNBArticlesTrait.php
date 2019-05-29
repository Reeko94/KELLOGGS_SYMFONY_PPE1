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

    /**
     * GetNBArticlesTrait constructor.
     * @param PanierRepository $panierRepository
     */
    public function __construct(PanierRepository $panierRepository)
    {
        $this->panierRepository = $panierRepository;
    }

    /**
     * @return int
     */
    public function getNBArticle()
    {
        if($user = $this->getUser()) {
            $panier = $this->panierRepository->checkPanier($user);
            if(count($panier) === 0) {
                // Panier non créer en base
                return 0;
            } else {
                $panierUser = $panier[0];
                $articlesPanierJSON = $panierUser->getArticles();
                $articlesPanierArray = json_decode($articlesPanierJSON,true);
                return (isset($articlesPanierArray[0]['idArticle'])) ? count($articlesPanierArray) : 0;
            }
        }
    }
}