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
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    use \App\Traits\GetNBArticlesTrait;

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

        $panier = $this->panierRepository->checkPanier($this->getUser())[0]->getArticles();

        $articles = json_decode($panier,true);


        foreach ($articles as $key => $value) {
            array_push($articles[$key],$this->articleRepository->findBy(['id' => $value['idarticle']])[0]);
        }

        return $this->render('panier/index.html.twig', [
            'nb' => $this->getNBArticle(),
            'infosarticles' => $articles,
            'controller_name' => 'PanierController',
            'user' => $this->getUser(),
            'infosPaiement' => $this->infosPaiementsRepository->getInfosByUser($this->getUser()),
            'infosLivraison' => $this->infosLivraisonsRepository->getInfosByUser($this->getUser())
        ]);
    }

    /**
     * @Route("/panier/delete", name="deletearticlepanier")
     */
    public function deleteArticlePanier(Request $request)
    {
        $panier = $this->panierRepository->checkPanier($this->getUser())[0]->getArticles();
        $articles = json_decode($panier,true);
        foreach ($articles as $article){
            if($article['idarticle'] == $request->get('id'))
                unset($articles[(array_keys($articles,$article)[0])]);
        }
        $this->panierRepository->setPanier($this->getUser(),$articles);
        return $this->redirectToRoute('panier');
    }

    /**
     * @param Request $request
     * @Route("/panier/update",name="updatepanier",methods="POST")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updatePanier(Request $request)
    {
        if ($user = $this->getUser()) {
            try {
                $this->panierRepository->updatePanier($user, intval($request->get('id')), true, $request->get('qte'),true);
                $this->addFlash('success', 'Article ajouté au panier avec succès');
            } catch (\Exception $e) {
                self::addFlash('error', 'Une erreur est survenue lors de l\'ajout au panier');
            }
            return $this->redirectToRoute('panier');
        }
    }

    /**
     * @Route("/panier/submit",name="panier_to_facture")
     * @throws \Exception
     */
    public function panierToFacture()
    {
        $panierjson = $this->panierRepository->checkPanier($this->getUser())[0];
        $panierarray = json_decode($panierjson->getArticles());

        $em = $this->getDoctrine()->getManager();

        $facture = new Factures();
        $facture->setClient($this->getUser());
        $date = new \DateTime(date('Y-m-d',time()));
        $facture->setDate($date);

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
    /**








    $em->flush();

     */
}
