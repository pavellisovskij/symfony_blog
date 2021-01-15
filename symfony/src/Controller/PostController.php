<?php

namespace App\Controller;

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
 * @Route("/admin/post")
 */
class PostController extends AbstractController
{

    /**
     * @Route("/{page}", name="post_index", requirements={"page"="\d+"}, methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
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
        $form = $this->createForm(SearchCategoryByChoiceType::class, null, [
            'action' => $this->generateUrl('post_index'),
            'method' => 'POST',//get
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('by')->getData() === 2) {
                $query = $postRepository->findByCategoryName($form->get('search_field')->getData());
            } elseif ($form->get('by')->getData() === 1) {
                $query = $postRepository->findByTitleUsingLike($form->get('search_field')->getData());
            }
        } else $query = $postRepository->findAll();

        $pagination = $paginator->paginate($query, $page, 5);
        $pagination->setCustomParameters([
            'align' => 'center'
        ]);

        return $this->render('post/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/p{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('post_index');
    }
}
