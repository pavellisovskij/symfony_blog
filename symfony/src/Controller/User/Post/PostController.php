<?php

namespace App\Controller\User\Post;

use App\Entity\Post;
use App\Form\PostType;
use App\Form\SearchCategoryByChoiceType;
use App\Repository\PostRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="user_post_")
 */
class PostController extends AbstractController
{

    /**
     * @Route("/{page}", name="index", requirements={"page"="\d+"}, methods={"GET"})
     * @param PostRepository $postRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param int $page
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(
        PostRepository $postRepository,
        PaginatorInterface $paginator,
        Request $request,
        int $page = 1
    ): Response {
        $criteria = [
            'main_page' => true,
            'status'    => true
        ];

        $query = $postRepository->findBy($criteria, [
            'created_at' => 'DESC'
        ]);

        $pagination = $paginator->paginate($query, $page, 5);
        $pagination->setCustomParameters([
            'align' => 'center'
        ]);

        return $this->render('user/post/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/all/{page}", name="all", requirements={"page"="\d+"}, methods={"GET"})
     * @param PostRepository $postRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param int $page
     * @return Response
     * @throws NonUniqueResultException
     */
    public function all(
        PostRepository $postRepository,
        PaginatorInterface $paginator,
        Request $request,
        int $page = 1
    ): Response {
        $query = $postRepository->findAllAndSortByDate();

        $pagination = $paginator->paginate($query, $page, 5);
        $pagination->setCustomParameters([
            'align' => 'center'
        ]);

        return $this->render('user/post/all.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("post/{id}", name="show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('user/post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
