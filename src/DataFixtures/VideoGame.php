<?php

namespace App\DataFixtures;

use App\Entity\VideoGame;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class VideoGameFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Non fonctionnel, erreur inconnu

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $videoGame = new VideoGame();

            $videoGame->setTitle($faker->sentence(3));
            $videoGame->setReleaseDate($faker->dateTimeBetween('-10 years', 'now'));
            $videoGame->setDescription($faker->sentence(10));

            $manager->persist($videoGame);
        }

        $manager->flush();
    }
}

