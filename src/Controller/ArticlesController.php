<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articles")
 */
class ArticlesController extends AbstractController
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
     * @var ArticlesRepository
     */
    private $articlesRepository;

    /**
     * ArticlesController constructor.
     * @param PanierRepository $panierRepository
     * @param ArticlesRepository $articlesRepository
     */
    public function __construct(PanierRepository $panierRepository, ArticlesRepository $articlesRepository)
    {
        $this->articlesRepository = $articlesRepository;
        $this->panierRepository = $panierRepository;
    }

    /**
     * @return string
     */
    private function generateUniqueFilename() : string
    {
        return md5(uniqid());
    }

    /**
     * @Route("/new", name="articles_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */

    public function new(Request $request): Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('urlMedia')->getData();
            $fileName = $this->generateUniqueFilename().'.'.$file->guessExtension();

            try {
                $file->move($this->getParameter('article_directory'),$fileName);
            } catch (FileException $e) {

            }

            $article->setUrlMedia($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('fabricants_index');
        }

        return $this->render('articles/new.html.twig', [
            'nb' => $this->getNBArticle(),
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="articles_edit", methods="GET|POST")
     * @param Request $request
     * @param Articles $article
     * @return Response
     * @throws \Throwable
     */
    public function edit(Request $request, Articles $article): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!is_null($form->get('urlMedia')->getData())) {
                $this->fileSystem->remove($this->getParameter('article_directory').'/'.$article->getUrlMedia());
                $file = $form->get('urlMedia')->getData();
                $fileName = $this->generateUniqueFilename().'.'.$file->guessExtension();

                try {
                    $file->move($this->getParameter('article_directory'),$fileName);
                } catch (FileException $e) {

                }
                $article->setUrlMedia($fileName);
            } else {
                $article->setUrlMedia($article->getUrlMedia());
            }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fabricants_show', ['id' => $article->getFabricant()->getId()]);
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'nb' => $this->getNBArticle()
        ]);
    }

    /**
     * @Route("/{id}", name="articles_show", methods="GET")
     * @param Articles $article
     * @return Response
     */
    public function show(Articles $article): Response
    {
        return $this->render('articles/show.html.twig', ['article' => $article,'nb' => $this->getNBArticle()]);
    }

    /**
     * @Route("/", name="articles_index", methods="GET")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('articles/index.html.twig', ['articles' => $this->articlesRepository->findAll(),'nb'=>$this->getNBArticle()]);
    }

    /**
     * @Route("/{id}", name="articles_delete", methods="DELETE")
     * @param Request $request
     * @param Articles $a
     * @return Response
     */
    public function delete(Request $request, Articles $a): Response
    {
        if ($this->isCsrfTokenValid('delete'.$a->getId(), $request->request->get('_token'))) {
            $paniers = $this->panierRepository->getAllPanier();
            $newPaniers = [];
            foreach ($paniers as $panier) {
                $articles = json_decode($panier['articles'],true);
                foreach ($articles as $article) {
                    if($article['idArticle'] == $a->getId()) {
                        $key = array_search($article, $articles);
                        unset($articles[$key]);
                    }
                }
                $tab = ["id" => $panier['id'], array_values($articles)];
                $newPaniers[] = $tab;
            }
            foreach ($newPaniers as $newPanier) {
                $this->panierRepository->updatePanierWithId(json_encode($newPanier[0]),intval($newPanier['id']));
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($a);
            $em->flush();
        }

        return $this->redirectToRoute('articles_index');
    }
}
