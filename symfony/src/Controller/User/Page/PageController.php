<?php

namespace App\Controller\User\Page;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/{page}", name="user_page_show", requirements={"page"="\/(\w|\/)+"}, methods={"GET"})
     */
    public function show(Page $page): Response
    {
        return $this->render('user/page/show.html.twig', [
            'page' => $page,
        ]);
    }
}
