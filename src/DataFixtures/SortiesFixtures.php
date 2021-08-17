<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SortiesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Instanciation de l'objet
        $sortie = new Sortie();

        //Valorisation des attributs
        $sortie->setNom('');


        $manager->persist($sortie);

        $manager->flush();
    }
}
