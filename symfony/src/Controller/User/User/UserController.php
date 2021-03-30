<?php

namespace App\Controller\User\User;

use App\Entity\User;
use App\Form\NewUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\DateTimeInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="user_user_")
 */
class UserController extends AbstractController
{
//    /**
//     * @Route("/list", name="index", methods={"GET"})
//     */
//    public function index(Request $request): Response
//    {
//        $form = $this->createForm(UserType::class, $this->getUser());
//        $form->handleRequest($request);
//        return $this->render('admin/user/index.html.twig', [
//            'users' => $userRepository->findAll(),
//        ]);
//    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(NewUserType::class, new User());
        $form->handleRequest($request);

        return $this->render('user/user/new.html.twig', [
            'form' => $form
        ]);
    }

//    /**
//     * @Route("/{id}", name="show", methods={"GET"})
//     */
//    public function show(User $user): Response
//    {
//        return $this->render('admin/user/show.html.twig', [
//            'user' => $user,
//        ]);
//    }
//
//    /**
//     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
//    {
//        $form = $this->createForm(UserType::class, $user, ['action' => 'edit']);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $user->setPassword($encoder->encodePassword($user, $form->get('password')->getData()));
//
//            if ($form->get('blocked')->getData() > 0 && $form->get('blocked')->getData() <= 7) {
//                $intervalService = new DateTimeInterval();
//                $user->setBlocked($intervalService->addTime($form->get('blocked')->getData()));
//            } else {
//                $user->setBlocked(null);
//            }
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('admin_user_index');
//        }
//
//        return $this->render('admin/user/edit.html.twig', [
//            'user' => $user,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}", name="delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, User $user): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($user);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('admin_user_index');
//    }
}
