<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Fabricants;
use App\Form\FabricantsType;
use App\Repository\ArticlesRepository;
use App\Repository\FabricantsRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fabricants")
 */
class FabricantsController extends AbstractController
{
    use \App\Traits\GetNBArticlesTrait;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var PanierRepository
     */
    private $panierRepository;

    /**
     * @Route("/", name="fabricants_index", methods="GET")
     * @param FabricantsRepository $fabricantsRepository
     * @return Response
     */
    public function index(FabricantsRepository $fabricantsRepository,ArticlesRepository $articlesRepository): Response
    {
        return $this->render('fabricants/index.html.twig', [
            'fabricants' => $fabricantsRepository->getAll(),
            'current_menu' => 'marques',
            'nb' => $this->getNBArticle()
        ]);
    }

    /**
     * @Route("/new", name="fabricants_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $fabricant = new Fabricants();
        $form = $this->createForm(FabricantsType::class, $fabricant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @ar Symfony\Component\HttpFoundation\File\UploadedFile $file
             */
            $file = $form->get('logo')->getData();
            $fileName = $this->generateUniqueFilename().'.'.$file->guessExtension();

            try {
                $file->move($this->getParameter('fabricant_directory'),$fileName);
            } catch (FileException $e) {

            }

            $fabricant->setLogo($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($fabricant);
            $em->flush();

            return $this->redirectToRoute('fabricants_index');
        }

        return $this->render('fabricants/new.html.twig', [
            'fabricant' => $fabricant,
            'form' => $form->createView(),
            'nb' => $this->getNBArticle()
        ]);
    }

    private function generateUniqueFilename()
    {
        return md5(uniqid());
    }

    /**
     * @Route("/{id}", name="fabricants_show", methods="GET")
     * @param Fabricants $fabricant
     * @param ArticlesRepository $articles
     * @return Response
     */
    public function show(Fabricants $fabricant,ArticlesRepository $articles): Response
    {
        $user = $this->getUser();
        if($user->getType() == 2) {
            $articlesByFabricant = $articles->findBy(['fabricant' => $fabricant->getId()]);
        } else {
            $articlesByFabricant = $articles->findBy(['fabricant' => $fabricant->getId(), 'disponibilite' => 1]);
        }
        return $this->render('fabricants/show.html.twig', [
            'fabricant' => $fabricant,
            'articles' => $articlesByFabricant,
            'nb' => $this->getNBArticle()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fabricants_edit", methods="GET|POST")
     * @param Request $request
     * @param Fabricants $fabricant
     * @return Response
     */
    public function edit(Request $request, Fabricants $fabricant): Response
    {
        $form = $this->createForm(FabricantsType::class, $fabricant);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if(!is_null($form->get('logo')->getData())) {
                $this->fileSystem->remove($this->getParameter('fabricant_directory').'/'.$fabricant->getLogo());
                $file = $form->get('logo')->getData();
                $fileName = $this->generateUniqueFilename().'.'.$file->guessExtension();

                try {
                    $file->move($this->getParameter('fabricant_directory'),$fileName);
                } catch (FileException $e) {

                }
                $fabricant->setLogo($fileName);
            } else {
                $fabricant->setLogo($fabricant->getLogo());
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fabricants_index', ['id' => $fabricant->getId()]);
        }



        return $this->render('fabricants/edit.html.twig', [
            'fabricant' => $fabricant,
            'form' => $form->createView(),
            'nb' => $this->getNBArticle()
        ]);
    }

    /**
     * @Route("/{id}", name="fabricants_delete", methods="DELETE")
     * @param Request $request
     * @param Fabricants $fabricant
     * @return Response
     */
    public function delete(Request $request, Fabricants $fabricant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fabricant->getId(), $request->request->get('_token'))) {
            $this->fileSystem->remove($this->getParameter('fabricant_directory').'/'.$fabricant->getLogo());
            $em = $this->getDoctrine()->getManager();
            $em->remove($fabricant);
            $em->flush();
        }

        return $this->redirectToRoute('fabricants_index');
    }


    /**
     * @Route("/add",name="addpanier",methods="POST")
     */
    public function addpanier(Request $request) : Response
    {
        if($user = $this->getUser())
        {
            $referer = substr($referer = $request->headers->get('referer'),-1);
            $this->panierRepository->addArticlePanier($user,intval($request->get('id')));
            $this->addFlash('success','Article ajouté au panier avec succès');
            return $this->redirectToRoute('fabricants_show',['id' => $referer]);
        }
    }
}
