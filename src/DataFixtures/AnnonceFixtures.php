<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnnonceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");

        for($i=1; $i<=10; $i++){
            $annonce = new Annonce();
            $annonce->setNom($faker->word)
                ->setDescription($faker->paragraph)
                ->setPrix($faker->numberBetween($min = 1, $max = 50));
            $manager->persist($annonce);
        }

        $manager->flush();
    }
}
