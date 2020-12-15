<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CategoryFixtures extends Fixture
{
    private $encoder;

    private $em;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager)
    {
        $this->encoder = $encoder;
        $this->em = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $categoryData = [
            0 => [
                'name'    => 'Технологии',
            ],
            1 => [
                'name'    => 'Наука',
            ],
            2 => [
                'name'    => 'Авто',
            ]
        ];

        foreach ($categoryData as $category) {
            $newCategory = new Category();
            $newCategory->setName($category['name']);
            $this->em->persist($newCategory);
        }

        $this->em->flush();
    }
}
