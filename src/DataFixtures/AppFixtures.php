<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\VideoGame;
use App\Entity\Category;
use App\Entity\Editor;
use App\Repository\EditorRepository;

class AppFixtures extends Fixture
{
    private EditorRepository $editorRepository;


    public function __construct(EditorRepository $editorRepository)
    {
        $this->editorRepository = $editorRepository;
    } 

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $categories = [
            'Aventure', 'Action', 'Tir', 'Mmorpg', 'Moba',
            'Point&Click', 'Strategie', 'Puzzle'
        ];

        $categoryObjects = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();

            $category->setName($faker->randomElement($categories));

            $manager->persist($category);

            $categoryObjects[] = $category;
        }

        $countries = [
            'France', 'États-Unis', 'Japon', 'Allemagne', 'Royaume-Uni',
            'Canada', 'Italie', 'Espagne', 'Brésil', 'Australie'
        ];

        for ($i = 0; $i < 10; $i++) {
            $editor = new Editor();

            $editor->setName($faker->company());
            $editor->setCountry($faker->randomElement($countries)); 

            $manager->persist($editor);

            // Référence pour lier aux VideoGame
            $this->addReference('editor_' . $i, $editor);
        }

        $manager->flush();

        $editors = $manager->getRepository(Editor::class)->findAll();

        if (empty($editors)) {
            throw new \RuntimeException('Aucun éditeur trouvé après le flush. Vérifie Editor entity / persistence.');
        }
    

        $coverImages = [
            'assassins_creed.jpg',
            'zelda.jpg',
            'mario_odyssey.jpg',
            'call_of_duty.jpg',
            'cyberpunk2077.jpg',
        ];

        for ($i = 0; $i < 10; $i++) {
            $videoGame = new VideoGame();
            $videoGame->setTitle($faker->sentence(3));
            $videoGame->setReleaseDate($faker->dateTimeBetween('now', '+1 month'));
            $videoGame->setDescription($faker->sentence(10));

          
            $randomEditor = $faker->randomElement($editors);
            $videoGame->setVideoGameEditor($randomEditor);


            $randomCategory = $faker->randomElement($categoryObjects);
            $videoGame->addVideoGameCategory($randomCategory);

            $videoGame->setCoverImage('/uploads/covers/' . $faker->randomElement($coverImages));

            $manager->persist($videoGame);
        }


        $manager->flush();
    }

}
