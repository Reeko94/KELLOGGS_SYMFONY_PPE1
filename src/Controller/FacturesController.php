<?php

namespace App\Controller;

use App\Entity\Factures;
use App\Repository\ArticlesRepository;
use App\Repository\ComposeRepository;
use App\Repository\FacturesRepository;
use App\Repository\InformationsLivraisonsRepository;
use App\Repository\InformationsPaiementsRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FacturesController extends AbstractController
{
    use \App\Traits\GetNBArticlesTrait;

    /**
     * @var FacturesRepository
     */
    private $factureRepository;

    /**
     * @var InformationsLivraisonsRepository
     */
    private $informationsLivraisonsRepository;

    /**
     * @var InformationsPaiementsRepository
     */
    private $informationsPaiementRepository;

    /**
     * @var ArticlesRepository
     */
    private $articlesRepository;

    /**
     * @var ComposeRepository
     */
    private $composeRepository;


    /**
     * FacturesController constructor.
     * @param FacturesRepository $factureRepository
     * @param InformationsLivraisonsRepository $informationsLivraisonsRepository
     * @param InformationsPaiementsRepository $informationsPaiementsRepository
     * @param ArticlesRepository $articlesRepository
     * @param ComposeRepository $composeRepository
     */
    public function __construct(FacturesRepository $factureRepository,
                                InformationsLivraisonsRepository $informationsLivraisonsRepository,
                                InformationsPaiementsRepository $informationsPaiementsRepository,
                                ArticlesRepository $articlesRepository,
                                ComposeRepository $composeRepository)
    {
        $this->factureRepository = $factureRepository;
        $this->informationsLivraisonsRepository = $informationsLivraisonsRepository;
        $this->informationsPaiementRepository = $informationsPaiementsRepository;
        $this->articlesRepository = $articlesRepository;
        $this->composeRepository = $composeRepository;
    }

    /**
     * @Route("/factures/{id}", name="factures")
     * @param Factures $factures
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Factures $factures)
    {
        $infosUser = $this->getUser();
        $factureUser = $this->factureRepository->findBy(['client' => $infosUser,'id' => $factures->getId()])[0];
        $infosLivraison = $this->informationsLivraisonsRepository->findBy(['utilisateur' => $infosUser])[0];
        $composeArticle = $this->composeRepository->findBy(['id_facture' => $factures->getId()]);
        $articlesFacture = [];
        $prixArticle = [];

        foreach ($composeArticle as $article => $value) {
            //$articlesRepository->findby(['id_facture' => $value->getIdArticle()->getLibelle()])
            $articlesFacture[$this->articlesRepository->find($value->getIdArticle())->getLibelle()] = $value->getQuantite();
            $prixArticle[$this->articlesRepository->find($value->getIdArticle())->getLibelle()] = $this->articlesRepository->find($value->getIdArticle())->getPrix();
        }


        /**
         * date_format($factureUser[0]->getDate(),'d/m/Y'));
        */
        return $this->render('factures/index.html.twig', [
            'facture' => $factureUser,
            'infosLivraison' => $infosLivraison,
            'articles' => $articlesFacture,
            'prix' => $prixArticle,
            'nb' => $this->getNBArticle()
        ]);
    }

}
