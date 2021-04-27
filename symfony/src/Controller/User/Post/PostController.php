<?php

namespace App\Controller\User\Post;

use App\Entity\Post;
use App\Entity\Rating;
use App\Repository\PostRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
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
    public function show(Post $post, RatingRepository $ratingRepository): Response
    {
        $numberOfGrades = $post->getRatings()->count();

        if ($numberOfGrades === 0) {
            $rating = 0;
        } else {
            $sumOfGrades = 0;

            foreach ($post->getRatings() as $grade) {
                $sumOfGrades = $sumOfGrades + $grade->getGrade();
            }

            $rating = $sumOfGrades / $numberOfGrades;
        }

        if (!$this->getUser()) $grade = null;
        else {
            $grade = $ratingRepository->findAUserPostedRating($this->getUser()->getId(), $post->getId());

            if ($grade === null) $grade = 0;
            else $grade = $grade->getGrade();
        }

        return $this->render('user/post/show.html.twig', [
            'post' => $post,
            'rating' => $rating,
            'grade' => $grade
        ]);
    }

    /**
     * @Route("/{id}/favorite", name="favorite", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function favorite(Request $request): Response
    {
        if ($this->getUser()) {
            $data = json_decode($request->getContent());
            $post = $this->getDoctrine()
                ->getRepository(Post::class)
                ->find($data->id);

            if ($this->getUser()->getFavorites()->contains($post)) {
                $this->getUser()->removeFavorite($post);
                $this->getDoctrine()->getManager()->flush();

                return $this->json(['result' => false]);
            } else {
                $this->getUser()->addFavorite($post);

                $this->getDoctrine()->getManager()->flush();

                return $this->json(['result' => true]);
            }
        } else {
            return $this->json(['result' => 'null']);
        }
    }

    /**
     * @Route("/{id}/evaluate/{grade}", name="evaluate", requirements={"id"="\d+", "grade"="\d+"}, methods={"POST"})
     */
    public function evaluate(
        Post $post,
        int $grade,
        Request $request,
        RatingRepository $ratingRepository
    ): Response {
        if ($this->getUser()) {
            $rating = $ratingRepository->findAUserPostedRating($this->getUser()->getId(), $post->getId());

            if ($rating == null) {
                $rating = new Rating();
                $rating->setGrade($grade);
                $rating->setPost($post);
                $rating->setUser($this->getUser());
            } else {
                $rating->setGrade($grade);
            }

            $em =  $this->getDoctrine()->getManager();
            $em->persist($rating);
            $em->flush();

            return $this->json(['result' => 'success']);
        } else {
            return $this->json(['result' => 'deny']);
        }
    }
}
