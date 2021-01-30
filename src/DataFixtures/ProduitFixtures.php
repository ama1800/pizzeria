<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProduitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 100; $i++) {
            $produit = new Produit();

            $produit->setProduitLibelle('Produit' . $i);
            $produit->setProduitPrix(mt_rand(0, 55));
            $produit->setDisponible(rand(0, 1));
            $cat = $this->getReference("Categorie_" . mt_rand(0, 9));
            $produit->setCategorie($cat);

            $manager->persist($produit);

            // enregistrer les produits dans une reférence
            $this->addReference('produit_' . $i, $produit);
        }


        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            CategorieFixtures::class,
            UserFixtures::class
        ];
    }
}
