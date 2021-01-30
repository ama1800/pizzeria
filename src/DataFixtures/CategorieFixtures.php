<?php

namespace App\DataFixtures;


use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategorieFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $categories = [
            1 => ['categorieLibelle' => 'PIZZA'],
            2 => ['categorieLibelle' => 'BOISSONS'],
            3 => ['categorieLibelle' => 'DESSERTS'],
            4 => ['categorieLibelle' => 'TACOS'],
            5 => ['categorieLibelle' => 'SALADES'],
            6 => ['categorieLibelle' => 'SAUCES'],
            7 => ['categorieLibelle' => 'MENUS'],
            8 => ['categorieLibelle' => 'EXTRAS'],
        ];
        foreach ($categories as $k => $v) {
            $cat = new Categorie();

            $cat->SetCategorieLibelle($v['categorieLibelle']);
            $manager->persist($cat);
            // enregistrer les categories dans une reférence
            $this->addReference('categorie_' . $k, $cat);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1; // ordre d'appel
    }
}
