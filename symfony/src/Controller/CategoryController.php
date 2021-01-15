<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\CategoryType;
use App\Form\SearchCategoryByChoiceType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET", "POST"})
     */
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {
        $form = $this->createForm(SearchCategoryByChoiceType::class, null, [
            'action' => $this->generateUrl('category_index'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('by')->getData() === 1) {
                $categories = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->findBy(
                        ['name' => $form->get('search_field')->getData()],
                        ['name' => 'ASC']
                    )
                ;

                return $this->render('category/index.html.twig', [
                    'categories' => $categories,
                    'form' => $form->createView()
                ]);
            }
            elseif ($form->get('by')->getData() === 2) {
                $posts = $this->getDoctrine()
                    ->getRepository(Post::class)
                    ->findByTitleUsingLike($form->get('search_field')->getData())
                ;

                $arrayOfCategories = [];
                foreach ($posts as $post) {
                    $arrayOfCategories[] = $post->getCategories()->getValues();
                }

                $categories = [];
                foreach ($arrayOfCategories as $element) {
                    foreach ($element as $category)
                        $categories[] = $category;
                }

                return $this->render('category/index.html.twig', [
                    'categories' => array_unique($categories),
                    'form' => $form->createView()
                ]);
            }
        }

        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
