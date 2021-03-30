<?php

namespace App\Controller\User\Category;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\CategoryType;
use App\Form\SearchCategoryByChoiceType;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="user_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/{page}", name="index", requirements={"page"="\d+"}, methods={"GET"})
     */
    public function index(
        CategoryRepository $categoryRepository,
        PaginatorInterface $paginator,
        int $page = 1
    ): Response {
        $query = $categoryRepository->findAll();

        $pagination = $paginator->paginate($query, $page, 10);
        $pagination->setCustomParameters([
            'align' => 'center'
        ]);

        return $this->render('user/category/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/{id}/posts/{page}", requirements={"page"="\d+"}, name="show", methods={"GET"})
     */
    public function show(
        Category $category,
        PaginatorInterface $paginator,
        int $page = 1
    ): Response {
        $pagination = $paginator->paginate($category->getPosts(), $page, 10);
        $pagination->setCustomParameters([
            'align' => 'center'
        ]);

        return $this->render('user/category/show.html.twig', [
            'pagination' => $pagination,
            'category' => $category
        ]);
    }
}
