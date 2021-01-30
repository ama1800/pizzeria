<?php

namespace App\DataFixtures;


use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        //     $this->loadUser($manager);
        //     $this->loadCategorie($manager);
        //     $this->loadProduit($manager);
        $manager->flush();
    }
}
