<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AnnonceFixtures extends Fixture
{

    private $passwordEncoder ;
    public function __construct ( UserPasswordEncoderInterface $passwordEncoder ) {
        $this -> passwordEncoder = $passwordEncoder ;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");

        $user = new User();
        $user->setUsername("romain")
            ->setPassword ($this -> passwordEncoder -> encodePassword($user ,'secret'));
        $manager -> persist($user);

        for($i=0; $i<=10; $i++){
            if($i % 2 == 0 || $i == 0){
                $categorie = new Categorie();
                $categorie->setNom($faker->word);
                $manager->persist($categorie);
            }

            $annonce = new Annonce();
            $annonce->setNom($faker->word)
                ->setDescription($faker->paragraph)
                ->setPrix($faker->numberBetween($min = 1, $max = 50))
                ->setAuteur($user)
                ->setCategorie($categorie);
            $manager->persist($annonce);
        }
        $manager->flush();
    }
}
