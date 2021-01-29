<?php

namespace App\DataFixtures;

use App\Entity\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $config = new Config();
        $config->setBrandName('Brand name...');
        $config->setAnonComments(true);
        $config->setPostComment(true);
        $config->setPremoderation(true);

        $manager->persist($config);
        $manager->flush();
    }
}
