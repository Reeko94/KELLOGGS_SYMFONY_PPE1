<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\FabricantsRepository;
use App\Repository\PanierRepository;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;

/**
 * Class DashboardController
 * @Route("/dashboard")
 * @package App\Controller
 */
class DashboardController extends AbstractController
{

    /**
     * @var FabricantsRepository
     */
    private $fabricantsRepository;

    /**
     * @var PanierRepository
     */
    private $panierRepository;

    /**
     * @var ArticlesRepository
     */
    private $articleRepository;

    /**
     * DashboardController constructor.
     * @param FabricantsRepository $fabricantsRepository
     * @param PanierRepository $panierRepository
     * @param ArticlesRepository $articlesRepository
     */
    public function __construct(FabricantsRepository $fabricantsRepository,PanierRepository $panierRepository,ArticlesRepository $articlesRepository)
    {
        $this->articleRepository = $articlesRepository;
        $this->fabricantsRepository = $fabricantsRepository;
        $this->panierRepository = $panierRepository;
    }

    /**
     * @Route("/",name="dashboard_index")
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/fabricants",name="dashboard_fabricants")
     */
    public function fabricantsview()
    {
        return $this->render('dashboard/fabricants.index.html.twig',[
            'fabricants' => $this->fabricantsRepository->findAll()
        ]);
    }

    /**
     * @Route("/getinfosformarques",name="ajax_getinfosformarques")
     * @param Request $request
     * @return false|string
     */
    public function getinfosformarque(Request $request)
    {
        $datas = $this->fabricantsRepository->getArrayResult($request->get('id'));
        return JsonResponse::create($datas[0]);
    }

    /**
     * @param Request $request
     * @Route("/updateinfosformarques",name="updateinfosformarques")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateinfosformarques(Request $request)
    {
        $id = $request->get('id');
        $libelle = $request->get('libelle');
        $this->fabricantsRepository->updateFabricant($id,$libelle);
        return $this->redirectToRoute('dashboard_index');
    }

    /**
     * @param Request $request
     * @Route("/deletemarque")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteMarque(Request $request)
    {
        $fabricantID = $this->fabricantsRepository->getArrayResult($request->get('id'))[0]['id'];
        $allPanier = $this->panierRepository->getAllPanier();
        // Pour tous les panier
        foreach ($allPanier as $panier){
            $article = json_decode($panier['articles']);
            // Pour tous les articles de chaque panier
            foreach ($article as $item) {
                if(!empty( (array) $item)){
                    // On recupere tous les infos de l'article
                    $arrayItem = (array)$item;
                    $articleFull = $this->articleRepository->findBy(['id'=>$arrayItem['idarticle']]);
                    // On récupere l'id fabricant de l'article
                    $idFabricantForArticle = $articleFull[0]->getFabricant()->getId();
                    // Si l'id du fabricant est l'id que l'on souhaite supprimer
                    if($idFabricantForArticle == $fabricantID) {
                        // Alors il faut supprimer l'article
                        $keyToDeleteFromArticle = (array_keys($article,$item))[0];
                        unset($article[$keyToDeleteFromArticle]);
                        // Reindexer de facon normal les articles avec le 1st à 0 et non 1
                        $reindex = array_values($article);
                        $newPanier = [];
                        foreach ($reindex as $jsonarticle) {
                            $newPanier[] = $jsonarticle;
                        }
                        if(count($newPanier) === 0)
                            $newPanier[] = new \stdClass();

                        $this->panierRepository->updatePanierWithId(json_encode($newPanier),$panier['id']);
                    }
                }
            }
        }
        $this->fabricantsRepository->deletefromid($request->get('id'));
        return $this->redirectToRoute('dashboard_index');
    }
}
