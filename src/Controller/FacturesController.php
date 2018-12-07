<?php

namespace App\Controller;

use App\Entity\Factures;
use App\Repository\ArticlesRepository;
use App\Repository\ComposeRepository;
use App\Repository\FacturesRepository;
use App\Repository\InformationsLivraisonsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FacturesController extends AbstractController
{
    /**
     * @Route("/factures/{id}", name="factures")
     */
    public function index(FacturesRepository $facturesRepository,Factures $factures,InformationsLivraisonsRepository $livraisonsRepository,ArticlesRepository $articlesRepository,ComposeRepository $composeRepository)
    {
        $infosUser = $this->getUser();
        $factureUser = $facturesRepository->findBy(['client' => $infosUser,'id' => $factures->getId()])[0];
        $infosLivraison = $livraisonsRepository->findBy(['utilisateur' => $infosUser])[0];
        $composeArticle = $composeRepository->findBy(['id_facture' => $factures->getId()]);
        $articlesFacture = [];
        $prixArticle = [];
        foreach ($composeArticle as $article => $value) {
            //$articlesRepository->findby(['id_facture' => $value->getIdArticle()->getLibelle()])
            $articlesFacture[$articlesRepository->find($value->getIdArticle())->getLibelle()] = $value->getQuantite();
            $prixArticle[$articlesRepository->find($value->getIdArticle())->getLibelle()] = $articlesRepository->find($value->getIdArticle())->getPrix();
        }



        /**
         * date_format($factureUser[0]->getDate(),'d/m/Y'));
        */
        return $this->render('factures/index.html.twig', [
            'facture' => $factureUser,
            'infosLivraison' => $infosLivraison,
            'articles' => $articlesFacture,
            'prix' => $prixArticle
        ]);
    }

}
