<?php

namespace App\Controller;

use App\Entity\Compose;
use App\Entity\Factures;
use App\Repository\ArticlesRepository;
use App\Repository\FacturesRepository;
use App\Repository\InformationsLivraisonsRepository;
use App\Repository\InformationsPaiementsRepository;
use App\Repository\PanierRepository;
use App\Repository\UtilisateurRepository;
use App\Traits\GetNBArticlesTrait;
use DateTime;
use Exception;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    use GetNBArticlesTrait;

    /**
     * @var ArticlesRepository
     */
    private $articleRepository;

    /**
     * @var PanierRepository
     */
    private $panierRepository;


    /**
     * @var InformationsLivraisonsRepository
     */
    private $infosLivraisonsRepository;

    /**
     * @var InformationsPaiementsRepository
     */
    private $infosPaiementsRepository;

    /**
     * @var UtilisateurRepository
     */
    private $utilisateurRepository;


    /**
     * @var FacturesRepository
     */
    private $factureRepository;

    /**
     * PanierController constructor.
     * @param UtilisateurRepository $utilisateurRepository
     * @param PanierRepository $panierRepository
     * @param ArticlesRepository $articleRepository
     * @param InformationsLivraisonsRepository $informationsLivraisonsRepository
     * @param InformationsPaiementsRepository $informationsPaiementsRepository
     * @param FacturesRepository $factureRepository
     */
    public function __construct(UtilisateurRepository $utilisateurRepository,PanierRepository $panierRepository,ArticlesRepository $articleRepository,InformationsLivraisonsRepository $informationsLivraisonsRepository,InformationsPaiementsRepository $informationsPaiementsRepository,FacturesRepository $factureRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->articleRepository = $articleRepository;
        $this->panierRepository = $panierRepository;
        $this->infosLivraisonsRepository = $informationsLivraisonsRepository;
        $this->infosPaiementsRepository = $informationsPaiementsRepository;
        $this->factureRepository = $factureRepository;
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function index()
    {
        if(!$this->getUser())
            return $this->redirectToRoute('home');



        $panier = $this->panierRepository->checkPanier($this->getUser());

        $articles = json_decode($panier[0]->getArticles(),true);
        $articlesWithDetails = [];
        $sum = 0;

        if(count($articles[0]) > 1) {

            foreach ($articles as $article) {
                $articleInBDD = $this->articleRepository->find($article['idArticle']);
                $article['urlMedia'] = $articleInBDD->getUrlMedia();
                $article['libelle'] = $articleInBDD->getLibelle();
                $article['prix'] = $articleInBDD->getPrix();
                $articlesWithDetails[] = $article;
                $sum += $articleInBDD->getPrix() * $article['qte'];
            }

            return $this->render('panier/index.html.twig', [
                'nb' => $this->getNBArticle(),
                'articles' => $articlesWithDetails,
                'controller_name' => 'PanierController',
                'sum' => $sum,
                'user' => $this->getUser(),
                'infosPaiement' => $this->infosPaiementsRepository->getInfosByUser($this->getUser()),
                'infosLivraison' => $this->infosLivraisonsRepository->getInfosByUser($this->getUser())
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/panier/delete", name="deletearticlepanier")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteArticlePanier(Request $request)
    {
        $panier = $this->panierRepository->checkPanier($this->getUser())[0]->getArticles();
        $articles = json_decode($panier,true);

        foreach ($articles as $article){
            if($article['idArticle'] == $request->get('id'))
                unset($articles[(array_keys($articles,$article)[0])]);
        }

        if(sizeof($articles) == 0) {
            $articles = [new stdClass()];
        }


        $this->panierRepository->setPanier($this->getUser(),json_encode($articles));

        return $this->redirectToRoute('panier');
    }

    /**
     * @param Request $request
     * @Route("/panier/update",name="updatepanier",methods="POST")
     * @return RedirectResponse
     */
    public function updatePanier(Request $request)
    {
        if ($user = $this->getUser()) {
            try {

                $panier  = $this->panierRepository->checkPanier($this->getUser()->getId());
                $idArticle = intval($request->get('id'));
                $panierArray = json_decode($panier[0]->getArticles(),true);

                $newPanier = [];
                foreach ($panierArray as $article) {
                    if($article['idArticle'] == $idArticle) {
                        $article['qte'] = intval($request->get('qte'));
                    }
                    array_push($newPanier,$article);
                }
                $JSONPanier = json_encode($newPanier);


                $this->panierRepository->updatePanier($user, $JSONPanier);
                $this->addFlash('success', 'Article ajouté au panier avec succès');
            } catch (Exception $e) {
                self::addFlash('error', 'Une erreur est survenue lors de l\'ajout au panier');
            }
            return $this->redirectToRoute('panier');
        }
    }

    /**
     * @Route("/panier/submit",name="panier_to_facture")
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function panierToFacture(Request $request)
    {
        //TODO: Ajouter le prix ttc dans l'entite facture
        $panierjson = $this->panierRepository->checkPanier($this->getUser())[0];
        $panierarray = json_decode($panierjson->getArticles());
        $em = $this->getDoctrine()->getManager();

        $facture = new Factures();
        $facture->setClient($this->getUser());
        $date = new DateTime(date('Y-m-d',time()));
        $facture->setDate($date);
        $facture->setTotalttc($request->get('totalttc'));


        $em->persist($facture);
        $em->flush();

        $lastInsertID = $this->factureRepository->findBy(['client'=>$this->getUser()],['id'=>'DESC'],1)[0]->getId();

        foreach ($panierarray as $article) {
            $compose = new Compose();
            $compose->setIdFacture($lastInsertID);
            $compose->setIdArticle($article->idarticle);
            $compose->setQuantite($article->qte);
            $em->persist($compose);
            $em->flush();
        }

        $this->panierRepository->destroyPanier($this->getUser());
        $this->addFlash('success','Panier payé avec succès');
        return $this->redirect('/');
    }
}
