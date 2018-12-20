<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commercial;
use App\Repository\ArticlesRepository;
use App\Repository\ClientRepository;
use App\Repository\CommercialRepository;
use App\Repository\FabricantsRepository;
use App\Repository\PanierRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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
     * @var UtilisateurRepository
     */
    private $utilisateurRepository;

    /**
     * @var CommercialRepository
     */
    private $commercialRepository;

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * DashboardController constructor.
     * @param CommercialRepository $commercialRepository
     * @param ClientRepository $clientRepository
     * @param UtilisateurRepository $utilisateurRepository
     * @param FabricantsRepository $fabricantsRepository
     * @param PanierRepository $panierRepository
     * @param ArticlesRepository $articlesRepository
     */
    public function __construct(CommercialRepository $commercialRepository,ClientRepository $clientRepository,UtilisateurRepository $utilisateurRepository,FabricantsRepository $fabricantsRepository,PanierRepository $panierRepository,ArticlesRepository $articlesRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->commercialRepository = $commercialRepository;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->articleRepository = $articlesRepository;
        $this->fabricantsRepository = $fabricantsRepository;
        $this->panierRepository = $panierRepository;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function isAdmin()
    {
        if($this->getUser()->getType() == 1)
            return $this->redirectToRoute('home');
    }

    /**
     * @Route("/",name="dashboard_index")
     */
    public function index()
    {
        $this->isAdmin();
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/fabricants",name="dashboard_fabricants")
     */
    public function fabricantsview()
    {
        $this->isAdmin();
        return $this->render('dashboard/fabricants.index.html.twig',[
            'fabricants' => $this->fabricantsRepository->findAll()
        ]);
    }

    /**
     * @Route("/users",name="dashboard_users")
     */
    public function allusersview()
    {
        $this->isAdmin();
        return $this->render('dashboard/users.index.html.twig',[
            'users' => $this->utilisateurRepository->orderByType()
        ]);
    }

    /**
     * @Route("/user/{id}",name="dashboard_user")
     * @param Request $request
     * @return Response
     */
    public function userview(Request $request)
    {
        $this->isAdmin();
        $user = $this->utilisateurRepository->findOneBy(['id' =>$request->get('id')]);
        return $this->render('dashboard/users.show.html.twig',[
            'user' => $user
        ]);
    }

    /**
     * @Route("/user/{id}/demote",name="dashboard_user_demote")
     * @param Request $request
     */
    public function demote(Request $request)
    {
        $this->isAdmin();
        $id = (int) $request->get('id');
        $commercial = $this->commercialRepository->findOneBy(['id' => $id]);
        $this->commercialRepository->setInactif($id);

        $em = $this->getDoctrine()->getManager();

        $client = new Client();
        $client->setId($id);
        $client->setEmail($commercial->getEmail());
        $client->setPassword($commercial->getPassword());
        $client->setType(1);
        $client->setNom($commercial->getNom());
        $client->setPrenom($commercial->getPrenom());
        $client->setActif(1);
        $client->setDateInscription(new \DateTime('now'));
        $client->setDateNaissance(new \DateTime('now'));

        $em->persist($client);
        $em->flush();
        $this->deleteAfterUpdate();
        return $this->redirectToRoute('dashboard_user',['id' => $client->getId()]);
    }

    /**
     * @param Request $request
     * @Route("/user/{id}/promote",name="dashboard_user_promote")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function promote(Request $request,ClientRepository $clientRepository)
    {
        $this->isAdmin();
        $id = (int) $request->get('id');
        $client = $clientRepository->findOneBy(['id'=>$id]);
        $this->clientRepository->setInactif($id);

        $commercial = new Commercial();
        $commercial->setId($id);
        $commercial->setEmail($client->getEmail());
        $commercial->setPassword($client->getPassword());
        $commercial->setNom($client->getNom());
        $commercial->setPrenom($client->getPrenom());
        $commercial->setType(2);
        $commercial->setDiscr('Commercial');
        $commercial->setActif(1);
        $commercial->setDateEntree(new \DateTime());
        $commercial->setPoste(' ');

        $em =$this->getDoctrine()->getManager();
        $em->persist($commercial);
        $em->flush();
        $this->deleteAfterUpdate();
        return $this->redirectToRoute('dashboard_user',['id'=>$commercial->getId()]);
    }

    /**
     * @Route("/getinfosformarques",name="ajax_getinfosformarques")
     * @param Request $request
     * @return false|string
     */
    public function getinfosformarque(Request $request)
    {
        $this->isAdmin();
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
        $this->isAdmin();
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
        $this->isAdmin();
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

    private function deleteAfterUpdate()
    {
        return $this->utilisateurRepository->deleteAfterUpdate();
    }
}
