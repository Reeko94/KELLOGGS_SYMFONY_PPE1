<?php

namespace App\Controller;

use App\Entity\InformationsPaiements;
use App\Entity\Utilisateur;
use App\Form\InformationsPaiementsType;
use App\Repository\InformationsPaiementsRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/informations/paiements")
 */
class InformationsPaiementsController extends AbstractController
{
    /**
     * @Route("/", name="informations_paiements_index", methods="GET")
     */
    public function index(InformationsPaiementsRepository $informationsPaiementsRepository): Response
    {
        return $this->render('informations_paiements/index.html.twig', ['informations_paiements' => $informationsPaiementsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="informations_paiements_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        if(is_null($this->getUser())){
            return $this->redirect('/');

        }
        else {

            $informationsPaiement = new InformationsPaiements();
            $form = $this->createForm(InformationsPaiementsType::class, $informationsPaiement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($informationsPaiement);
                $em->flush();

                return $this->redirectToRoute('informations_paiements_index');
            }

            return $this->render('informations_paiements/new.html.twig', [
                'informations_paiement' => $informationsPaiement,
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/{id}", name="informations_paiements_show", methods="GET")
     */
    public function show(InformationsPaiements $informationsPaiement): Response
    {
        return $this->render('informations_paiements/show.html.twig', ['informations_paiement' => $informationsPaiement]);
    }

    /**
     * @Route("/{id}/edit", name="informations_paiements_edit", methods="GET|POST")
     */
    public function edit(Request $request, InformationsPaiements $informationsPaiement): Response
    {
        $form = $this->createForm(InformationsPaiementsType::class, $informationsPaiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('informations_paiements_index', ['id' => $informationsPaiement->getId()]);
        }

        return $this->render('informations_paiements/edit.html.twig', [
            'informations_paiement' => $informationsPaiement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="informations_paiements_delete", methods="DELETE")
     */
    public function delete(Request $request, InformationsPaiements $informationsPaiement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$informationsPaiement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($informationsPaiement);
            $em->flush();
        }

        return $this->redirectToRoute('informations_paiements_index');
    }

    public function  ajouterAction()
    {
        $Utilisateur = new Utilisateur();
        $Utilisateur->setInformationsPaiements($this->getUser());
        $form = $this->createForm(new InformationsPaiementsType(), $Utilisateur);
    }
}
