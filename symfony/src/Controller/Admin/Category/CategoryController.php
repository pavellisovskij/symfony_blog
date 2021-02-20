<?php

namespace App\Controller\Admin\Category;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\AddPostToCategoryType;
use App\Form\CategoryType;
use App\Form\SearchCategoryByChoiceType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(
        CategoryRepository $categoryRepository,
        Request $request
    ): Response {
        $form = $this->createForm(SearchCategoryByChoiceType::class, null, [
            'action' => $this->generateUrl('admin_category_index'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('by')->getData() === 2) {
                $query = $categoryRepository->findByNameUsingLike($form->get('search_field')->getData());
            } elseif ($form->get('by')->getData() === 1) {
                $query = $categoryRepository->findByPostTitleUsingLike($form->get('search_field')->getData());
            }
        } else $query = $categoryRepository->findAllSorted();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $query,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
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

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET", "POST"})
     */
    public function show(Category $category, Request $request, PostRepository $postRepository): Response
    {
        $form = $this->createForm(AddPostToCategoryType::class, null, [
            'action' => $this->generateUrl('admin_category_show', ['id' => $category->getId()]),
            'method' => 'POST',
            'category_id' => $category->getId()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $postRepository->find($form->get('title')->getData());
            $category->addPost($post);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('post_status', 'Post "' . $post->getTitle() . '" added to ' . $category->getName());

            return $this->redirectToRoute('admin_category_show', ['id' => $category->getId()]);
        }

        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_category_index');
    }

    /**
     * @Route("/{category}/post/{post}/remove", name="remove_post", methods={"GET"})
     */
    public function removePost(Category $category, Post $post): Response
    {
        $category->removePost($post);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($category);
        $entityManager->flush();
        $this->addFlash('post_status', 'Post "' . $post->getTitle() . '" removed from ' . $category->getName());

        return $this->redirectToRoute('admin_category_show', [
            'id' => $category->getId()
        ]);
    }
}
