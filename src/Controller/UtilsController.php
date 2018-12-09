<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UtilsController extends AbstractController
{
    /**
     * @Route("/utils", name="utils")
     */
    public function index()
    {
        return $this->render('utils/index.html.twig', [
            'controller_name' => 'UtilsController',
        ]);
    }

    public static function getNbArticleInCart()
    {
        $nb = 0;
        if(!isset($_SESSION['panier']))
            $_SESSION['panier'] = [];

        foreach ($_SESSION['panier'] as $item=>$value)
        {
            $nb = $nb + 1;
        }
        return $nb;
    }
}
