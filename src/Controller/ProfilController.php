<?php

namespace App\Controller;

use App\Entity\InformationsLivraisons;
use App\Entity\InformationsPaiements;
use App\Repository\ClientRepository;
use App\Repository\FacturesRepository;
use App\Repository\InformationsLivraisonsRepository;
use App\Repository\InformationsPaiementsRepository;
use App\Repository\PanierRepository;
use App\Repository\UtilisateurRepository;
use App\Traits\GetNBArticlesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    use GetNBArticlesTrait;

    /**
     * @var FacturesRepository
     */
    private $facturesRepository;

    /**
     * @var InformationsLivraisonsRepository
     */
    private $livraisonRepository;

    /**
     * @var InformationsPaiementsRepository
     */
    private $paiementRepository;

    /**
     * @var UtilisateurRepository
     */
    private $utilisateurRepository;

    /**
     * @var ClientRepository
     */
    private $ClientRepository;

    public function __construct(ClientRepository $clientRepository,UtilisateurRepository $utilisateurRepository,PanierRepository $panierRepository,FacturesRepository $facturesRepository,InformationsLivraisonsRepository $informationsLivraisonsRepository,InformationsPaiementsRepository $informationsPaiementsRepository)
    {
        $this->ClientRepository = $clientRepository;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->panierRepository = $panierRepository;
        $this->facturesRepository = $facturesRepository;
        $this->livraisonRepository = $informationsLivraisonsRepository;
        $this->paiementRepository = $informationsPaiementsRepository;
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function index()
    {
        if(!$user = $this->getUser())
            return $this->redirect('/');

        $factureUser = $this->facturesRepository->findBy(['client'=>$user]);
        $livraisonUser = $this->livraisonRepository->findBy(['utilisateur' => $user]);
        $paiementUser = $this->paiementRepository->findBy(['Utilisateur' => $user]);
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'livraison' => $livraisonUser,
            'factures' => $factureUser,
            'paiement' => $paiementUser,
            'nb' => $this->getNBArticle()
        ]);
    }

    /**
     * @Route("update_infos_personnelles",name="update_infos_personnelles")
     */
    public function updatetDetailsUser(Request $request)
    {
        $datas = [
            'nom' =>$request->get("nom"),
            'prenom' => $request->get('prenom'),
            'datenaissance' => $request->get('datenaissance')." 00:00:00"
        ];
        $this->utilisateurRepository->updateUtilisateur($this->getUser(),$datas);
        return $this->redirectToRoute('profil');
    }

    /**
     * @param Request $request
     * @Route("update_infos_paiement",name="update_infos_paiement")
     */
    public function updatePaiementUser(Request $request)
    {
        $datas = [
            'numero' => $request->get('numerocarte'),
            'date' => $request->get('datevalidite'),
            'cryptogramme' => $request->get('cryptogramme'),
            'nom' =>$request->get("nom"),
            'prenom' => $request->get('prenom'),
        ];
        if(sizeof(intval($request->get('cryptogramme'))) > 3) {

            return $this->redirectToRoute('profil');
        }

        if(!$this->Luhn($datas['numero'])) {
            $this->addFlash('danger', 'Numéro de carte incorrect');
            return $this->redirectToRoute('profil');
        }
        $this->paiementRepository->updateInfosUser($this->getUser(),$datas);
        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("update_infos_livraison",name="update_infos_livraison")
     */
    public function updateLivraisonUser(Request $request)
    {
        $datas = [
            'numero' => $request->get('numero'),
            'complement'=> $request->get('complement'),
            'rue'=> $request->get('rue'),
            'codepostal'=> $request->get('codepostal'),
            'ville'=> $request->get('ville'),
            'pays'=> $request->get('pays'),
        ];
        $this->livraisonRepository->updateInfosUser($this->getUser(),$datas);
        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("delete_paiement",name="delete_paiement")
     */
    public function deleteInfosPaiementUser()
    {
        $this->paiementRepository->deleteFromUser($this->getUser());
        return $this->redirectToRoute('profil');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("add_infos_paiement",name="add_infos_paiements")
     */
    public function addInfosPaiements(Request $request)
    {
        $datas = [
            'numero' => $request->get('numerocarte'),
            'date' => $request->get('datevalidite'),
            'cryptogramme' => $request->get('cryptogramme'),
            'nom' =>$request->get("nom"),
            'prenom' => $request->get('prenom'),
        ];
        if(!$this->Luhn($datas['numero'])) {
            $this->addFlash('danger', 'Numéro de carte incorrect');
            return $this->redirectToRoute('profil');
        }

        $em = $this->getDoctrine()->getManager();
        $infosPaiement = new InformationsPaiements();
        $infosPaiement->setUtilisateur($this->getUser());
        $infosPaiement->setNom($datas['nom']);
        $infosPaiement->setPrenom($datas['prenom']);
        $infosPaiement->setCryptogramme($datas['cryptogramme']);
        $infosPaiement->setNumero($datas['numero']);
        $infosPaiement->setDate(\DateTime::createFromFormat('Y-m-d',$datas['date']));
        $em->persist($infosPaiement);
        $em->flush();

        return $this->redirectToRoute('profil');
    }

    /**
     * @param Request $request
     * @Route("add_infos_livraison",name="add_infos_livraison")
     */
    public function addInfosLivraisons(Request $request)
    {
        $datas = [
            'numero' => $request->get('numero'),
            'complement'=> $request->get('complement'),
            'rue'=> $request->get('rue'),
            'codepostal'=> $request->get('codepostal'),
            'ville'=> $request->get('ville'),
            'pays'=> $request->get('pays'),
        ];

        $em = $this->getDoctrine()->getManager();
        $infosLivraison = new InformationsLivraisons();
        $infosLivraison->setNumero($datas['numero']);
        $infosLivraison->setUtilisateur($this->getUser());
        $infosLivraison->setCodepostal($datas['codepostal']);
        $infosLivraison->setComplement($datas['complement']);
        $infosLivraison->setPays($datas['pays']);
        $infosLivraison->setRue($datas['rue']);
        $infosLivraison->setVille($datas['ville']);

        $em->persist($infosLivraison);
        $em->flush();

        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("delete_livraison",name="delete_livraison")
     */
    public function deleteInfosLivraison()
    {
        $this->livraisonRepository->deleteFromUser($this->getUser());
        return $this->redirectToRoute('profil');
    }

    /**
     * Algo de LUNH
     * @param $numero
     * @return bool
     */
    private function Luhn($numero,$longueur = 16){
        // On passe à la fonction la variable contenant le numéro à vérifier
        // et la longueur qu'il doit impérativement avoir
        if (strlen($numero)==$longueur){ // si la longueur est bonne
            /* on décompose le numéro dans un tableau  */
                for ($i=0;$i<$longueur;$i++){
                    $tableauChiffresNumero[$i]=substr($numero,$i,1);
                }
        /* on parcours le tableau pour additionner les chiffres */
            $luhn=0; // clef de luhn à tester
            for ($i=0;$i<$longueur;$i++){
                if ($i%2==0){ // si le rang est pair (0,2,4 etc.)
                    if(($tableauChiffresNumero[$i]*2) > 9){
                    // On regarde si son double est > à 9
                        $tableauChiffresNumero[$i]=($tableauChiffresNumero[$i]*2)-9; //si oui on lui retire 9
                        // et on remplace la valeur
                        // par ce double corrigé
                    }else{
                        $tableauChiffresNumero[$i]=$tableauChiffresNumero[$i]*2; // si non on remplace la valeur
                        // par le double
                    }
                }
                $luhn=$luhn+$tableauChiffresNumero[$i];
                // on additionne le chiffre à la clef de luhn
            }

            /* test de la divition par 10 */
            if($luhn%10==0){
                return true;
            }
            else{
                return false;
            }
        }else{
            return false;
            // la valeur fournie n'est pas conforme (caractère non numérique ou mauvaise
            // longueur)
        }
    }
}
