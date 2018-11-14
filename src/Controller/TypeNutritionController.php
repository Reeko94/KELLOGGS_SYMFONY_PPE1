<?php

namespace App\Controller;

use App\Entity\TypeNutrition;
use App\Form\TypeNutritionType;
use App\Repository\TypeNutritionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/typenutrition")
 */
class TypeNutritionController extends AbstractController
{
    /**
     * @Route("/", name="type_nutrition_index", methods="GET")
     * @param TypeNutritionRepository $typeNutritionRepository
     * @return Response
     */
    public function index(TypeNutritionRepository $typeNutritionRepository): Response
    {
        return $this->render('type_nutrition/index.html.twig', ['type_nutritions' => $typeNutritionRepository->findAll()]);
    }

    /**
     * @Route("/new", name="type_nutrition_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $typeNutrition = new TypeNutrition();
        $form = $this->createForm(TypeNutritionType::class, $typeNutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeNutrition);
            $em->flush();

            return $this->redirectToRoute('type_nutrition_index');
        }

        return $this->render('type_nutrition/new.html.twig', [
            'type_nutrition' => $typeNutrition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_nutrition_show", methods="GET")
     * @param TypeNutrition $typeNutrition
     * @return Response
     */
    public function show(TypeNutrition $typeNutrition): Response
    {
        return $this->render('type_nutrition/show.html.twig', ['type_nutrition' => $typeNutrition]);
    }

    /**
     * @Route("/{id}/edit", name="type_nutrition_edit", methods="GET|POST")
     * @param Request $request
     * @param TypeNutrition $typeNutrition
     * @return Response
     */
    public function edit(Request $request, TypeNutrition $typeNutrition): Response
    {
        $form = $this->createForm(TypeNutritionType::class, $typeNutrition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_nutrition_index', ['id' => $typeNutrition->getId()]);
        }

        return $this->render('type_nutrition/edit.html.twig', [
            'type_nutrition' => $typeNutrition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_nutrition_delete", methods="DELETE")
     * @param Request $request
     * @param TypeNutrition $typeNutrition
     * @return Response
     */
    public function delete(Request $request, TypeNutrition $typeNutrition): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeNutrition->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeNutrition);
            $em->flush();
        }

        return $this->redirectToRoute('type_nutrition_index');
    }
}
