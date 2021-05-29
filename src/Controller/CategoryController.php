<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * 
     * @Route("/", name = "index")
     * @return Response
     * 
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name = "new")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute("category_index");
        }
        return $this->render("category/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{categoryName}", name = "show")
     */
    public function show(string $categoryName)
    {
        $categoryId = $this->getDoctrine()->getRepository(Category::class)->findBy(['name' => $categoryName]);

        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findBy(['category' => $categoryId],
                ['id' => 'DESC'], 3);

        if (!$categoryId){
            throw $this->createNotFoundException(
                'No ' . $categoryName . ' serie found'
            );
        }
        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'category' => $categoryName,
        ]);
    }
}
