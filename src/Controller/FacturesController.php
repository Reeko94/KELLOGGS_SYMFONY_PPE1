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
use Symfony\Component\HttpFoundation\Response;
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
     * @var PanierRepository
     */
    private $panierRepository;

    /**
     * FacturesController constructor.
     * @param FacturesRepository $factureRepository
     * @param InformationsLivraisonsRepository $informationsLivraisonsRepository
     * @param InformationsPaiementsRepository $informationsPaiementsRepository
     * @param ArticlesRepository $articlesRepository
     * @param ComposeRepository $composeRepository
     * @param PanierRepository $panierRepository
     */
    public function __construct(FacturesRepository $factureRepository,
                                InformationsLivraisonsRepository $informationsLivraisonsRepository,
                                InformationsPaiementsRepository $informationsPaiementsRepository,
                                ArticlesRepository $articlesRepository,
                                ComposeRepository $composeRepository,PanierRepository $panierRepository)
    {
        $this->factureRepository = $factureRepository;
        $this->informationsLivraisonsRepository = $informationsLivraisonsRepository;
        $this->informationsPaiementRepository = $informationsPaiementsRepository;
        $this->articlesRepository = $articlesRepository;
        $this->composeRepository = $composeRepository;
        $this->panierRepository = $panierRepository;
    }

    /**
     * @Route("/factures/{id}", name="factures")
     * @param Factures $facture
     * @return Response
     */
    public function index(Factures $facture)
    {
        $user = $this->getUser();
        $composeArticleInFacture = $this->composeRepository->findBy(['id_facture' => $facture->getId()]);
        $livraison = $this->informationsLivraisonsRepository->getInfosByUser($user->getId())[0];
        $totalttc = 0;

        foreach ($composeArticleInFacture as $composeArticle) {
            $articleInFacture = [];
            $article = $this->articlesRepository->find($composeArticle->getIdArticle());
            $articleInFacture[] = ["qte" => $composeArticle->getQuantite(),"article" => $article];
            $totalttc += $composeArticle->getQuantite() * $article->getPrix();
        }


        /**
         * date_format($factureUser[0]->getDate(),'d/m/Y'));
        */
        return $this->render('factures/index.html.twig', [
            'facture' => $facture,
            'user' => $user,
            'infosLivraison' => $livraison,
            'articles' => $articleInFacture,
            'nb' => $this->getNBArticle(),
            'totalttc' => $totalttc
        ]);
    }

}
