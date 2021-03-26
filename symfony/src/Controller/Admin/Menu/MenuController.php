<?php

namespace App\Controller\Admin\Menu;

use App\Entity\Menu;
use App\Entity\MenuItem;
use App\Entity\Page;
use App\Form\AddToMenuType;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/menu", name="admin_menu_")
 */
class MenuController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Menu $menu): Response
    {
        $itemForm = $this->createForm(AddToMenuType::class, null, [
            'pages' => $this->getDoctrine()
                ->getRepository(Page::class)
                ->getTitlesOfPublishedPages(),
            'action' => $this->generateUrl('admin_menu_add', ['id' => $menu->getId()]),
            'method' => 'POST',
        ]);
        $itemForm->handleRequest($request);

        return $this->render('admin/menu/edit.html.twig', [
            'menu' => $menu,
            'itemForm' => $itemForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/add-item", name="add", methods={"POST"})
     */
    public function addItem(Request $request, Menu $menu): Response
    {
        $addToItem = $request->request->get('add_to_menu');

        $page = $this->getDoctrine()
            ->getRepository(Page::class)
            ->find($addToItem['page']);

        $menuItem = new MenuItem();
        $menuItem->setMenu($menu);
        $menuItem->setPage($page);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($menuItem);
        $entityManager->flush();

        return $this->redirectToRoute('admin_menu_edit', [
            'id' => $menu->getId()
        ]);
    }
}
