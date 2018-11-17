<?php

namespace App\Controller;

use App\Entity\Fabricants;
use App\Form\Fabricants1Type;
use App\Form\FabricantsEditType;
use App\Form\FabricantsType;
use App\Repository\FabricantsRepository;
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
    /**
     * @var Filesystem
     */
    private $fileSystem;

    public function __construct()
    {
        $this->fileSystem = new Filesystem();
    }

    /**
     * @Route("/", name="fabricants_index", methods="GET")
     */
    public function index(FabricantsRepository $fabricantsRepository): Response
    {
        return $this->render('fabricants/index.html.twig', ['fabricants' => $fabricantsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="fabricants_new", methods="GET|POST")
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
        ]);
    }

    private function generateUniqueFilename()
    {
        return md5(uniqid());
    }

    /**
     * @Route("/{id}", name="fabricants_show", methods="GET")
     */
    public function show(Fabricants $fabricant): Response
    {
        return $this->render('fabricants/show.html.twig', ['fabricant' => $fabricant]);
    }

    /**
     * @Route("/{id}/edit", name="fabricants_edit", methods="GET|POST")
     */
    public function edit(Request $request, Fabricants $fabricant): Response
    {
        $form = $this->createForm(FabricantsType::class, $fabricant);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->fileSystem->remove($this->getParameter('fabricant_directory').'/'.$fabricant->getLogo());
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

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fabricants_index', ['id' => $fabricant->getId()]);
        }



        return $this->render('fabricants/edit.html.twig', [
            'fabricant' => $fabricant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fabricants_delete", methods="DELETE")
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
}
