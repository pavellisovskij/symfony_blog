<?php

namespace App\Controller\Admin\Post;

use App\Entity\File;
use App\Entity\Post;
use App\Form\PostType;
use App\Form\SearchCategoryByChoiceType;
use App\Repository\PostRepository;
use App\Service\FileManager;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post", name="admin_post_")
 */
class PostController extends AbstractController
{

    /**
     * @Route("/list/{page}", name="index", requirements={"page"="\d+"}, methods={"GET"})
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
            'action' => $this->generateUrl('admin_post_index'),
            'method' => 'GET',
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

        return $this->render('admin/post/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            'action' => $this->generateUrl('admin_post_new'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($form->get('preview')->getData() !== null) {
                $file = new File();
                $fileManager = new FileManager($this->getParameter('uploads_directory'));
                $file->setPath($fileManager->upload($form->get('preview')->getData(),'post_previews'));
                $post->setFile($file);
                $entityManager->persist($file);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('admin/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('preview')->getData() !== null) {
                $fileManager = new FileManager($this->getParameter('uploads_directory'));

                $fileManager->delete($post->getFile()->getPath());

                $post->getFile()->setPath($fileManager->upload($form->get('preview')->getData(),'post_previews'));
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_post_index');
    }
}
