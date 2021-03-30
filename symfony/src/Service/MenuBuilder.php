<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use App\Repository\MenuRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    private $factory;
    private $mr;
    private $cr;

    /**
     * Add any other dependency you need...
     */
    public function __construct(
        FactoryInterface $factory,
        MenuRepository $menuRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->factory = $factory;
        $this->mr = $menuRepository;
        $this->cr = $categoryRepository;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menuItems = $this->mr->findBy(['name' => 'main']);
        $menuItems = $menuItems['0']->getMenuItems()->toArray();

        $menu = $this->factory->createItem('root');

        $menu->addChild('Posts', ['route' => 'user_post_all']);
        $menu->addChild('Categories', ['route' => 'user_category_index']);

        foreach ($menuItems as $item) {
            $menu->addChild($item->getPage()->getTitle(), [
                'uri' => $item->getPage()->getLink()]
            );
        }

        if ($options['withStyles'] === true) {
            $menu->setChildrenAttribute('class', 'navbar-nav mr-auto');
            foreach ($menu as $item) {
                $item->setAttribute('class', 'nav-item')
                    ->setLinkAttribute('class', 'nav-link');
            }
        }

        return $menu;
    }

    public function createAdminSidebarMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Categories', ['route' => 'admin_category_index']);
        $menu->addChild('Posts', ['route' => 'admin_post_index']);
        $menu->addChild('Comments', ['route' => 'admin_comment_index']);
        $menu->addChild('Pages', ['route' => 'admin_page_index']);
        $menu->addChild('Menu', [
            'route' => 'admin_menu_edit',
            'routeParameters' => ['id' => 1]
        ]);
        $menu->addChild('Users', ['route' => 'admin_user_index']);
        $menu->addChild('Config', ['route' => 'admin_config_index']);

        $menu->setChildrenAttribute('class', 'nav flex-column');
        foreach ($menu as $item) {
            $item->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }

        return $menu;
    }

    public function createUserSidebarCategories(array $options): ItemInterface
    {
        $categories= $this->cr->findAll();

        $menu = $this->factory->createItem('root');

        foreach ($categories as $category) {
            $menu->addChild($category->getName(), [
                'route' => 'user_category_show',
                'routeParameters' => ['id' => $category->getId()]
            ]);
        }

        $menu->setChildrenAttribute('class', 'nav flex-column');
        foreach ($menu as $item) {
            $item->setAttribute('class', 'nav-item')
                ->setLinkAttribute('class', 'nav-link');
        }

        return $menu;
    }
}