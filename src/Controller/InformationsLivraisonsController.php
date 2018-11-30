<?php

namespace App\Controller;

use App\Entity\InformationsLivraisons;
use App\Form\InformationsLivraisonsType;
use App\Repository\InformationsLivraisonsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/informationslivraisons")
 */
class InformationsLivraisonsController extends AbstractController
{
    /**
     * @Route("/", name="informations_livraisons_index", methods="GET")
     */
    public function index(InformationsLivraisonsRepository $informationsLivraisonsRepository): Response
    {
        return $this->render('informations_livraisons/index.html.twig', ['informations_livraisons' => $informationsLivraisonsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="informations_livraisons_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $informationsLivraison = new InformationsLivraisons();
        $form = $this->createForm(InformationsLivraisonsType::class, $informationsLivraison);
        $form->handleRequest($request);
        $utilisateur = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $informationsLivraison->setUtilisateur($utilisateur);
            $em->persist($informationsLivraison);
            $em->flush();

            return $this->redirectToRoute('informations_livraisons_index');
        }

        return $this->render('informations_livraisons/new.html.twig', [
            'informations_livraison' => $informationsLivraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="informations_livraisons_show", methods="GET")
     */
    public function show(InformationsLivraisons $informationsLivraison): Response
    {
        return $this->render('informations_livraisons/show.html.twig', ['informations_livraison' => $informationsLivraison]);
    }

    /**
     * @Route("/{id}/edit", name="informations_livraisons_edit", methods="GET|POST")
     */
    public function edit(Request $request, InformationsLivraisons $informationsLivraison): Response
    {
        $form = $this->createForm(InformationsLivraisonsType::class, $informationsLivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('informations_livraisons_index', ['id' => $informationsLivraison->getId()]);
        }

        return $this->render('informations_livraisons/edit.html.twig', [
            'informations_livraison' => $informationsLivraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="informations_livraisons_delete", methods="DELETE")
     */
    public function delete(Request $request, InformationsLivraisons $informationsLivraison): Response
    {
        if ($this->isCsrfTokenValid('delete'.$informationsLivraison->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($informationsLivraison);
            $em->flush();
        }

        return $this->redirectToRoute('informations_livraisons_index');
    }
}
