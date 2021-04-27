<?php

namespace App\Controller\User\User;

use App\Entity\Post;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\NewUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\DateTimeInterval;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="user_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(NewUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData() === $form->get('retry_password')->getData()) {
                $user->setPassword($encoder->encodePassword($user, $form->get('password')->getData()));
                $user->setEmail($form->get('email')->getData());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('user_post_index');
            } else {
                $this->addFlash('wrong_pass', 'fields "password" and "retry password" do not match');
                return $this->redirectToRoute('user_user_new');
            }
        }

        return $this->render('user/user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        if ($user->getUsername() === $this->getUser()->getUsername()) {
            return $this->render('user/user/show.html.twig', [
                'user' => $user,
            ]);
        } else {
            throw new HttpException(403, 'Access denied.');
        }
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        if ($user->getUsername() === $this->getUser()->getUsername()) {
            $form = $this->createForm(ChangePasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (
                    $form->get('new_password')->getData() === $form->get('retry_new_password')->getData() &&
                    $encoder->isPasswordValid($user, $form->get('old_password')->getData())
                ) {
                    $user->setPassword($encoder->encodePassword($user, $form->get('new_password')->getData()));

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'password changed successfully');
                } else {
                    $this->addFlash('danger', 'incorrect data');
                }

                return $this->redirectToRoute('user_user_edit', ['id' => $this->getUser()->getId()]);
            }

            return $this->render('user/user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
            throw new HttpException(403, 'Access denied.');
        }
    }

    /**
     * @Route("/{id}/favorites/{page}", name="favorites", requirements={"page"="\d+"}, methods={"GET"})
     */
    public function favorites(User $user, PaginatorInterface $paginator, int $page = 1): Response
    {
        if ($user->getUsername() === $this->getUser()->getUsername()) {

            $pagination = $paginator->paginate($user->getFavorites(), $page, 5);
            $pagination->setCustomParameters([
                'align' => 'center'
            ]);

            return $this->render('user/user/favorites.html.twig', [
                'user' => $user,
                'pagination' => $pagination,
            ]);
        } else {
            throw new HttpException(403, 'Access denied.');
        }
    }
}
