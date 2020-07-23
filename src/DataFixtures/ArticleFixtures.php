<?php

namespace App\DataFixtures;

use App\Entity\Article\Article;
use App\Repository\Account\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    private $user;

    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $admin = $this->user->findOneBy([
            'username' => 'admin'
        ]);

        for ($i = 0; $i < 100; $i++){

            $article = new Article();

            $article->setTitle($faker->text(60))
                    ->setContent($faker->paragraph(40))
                    ->setTags(['tags1', 'tags2', 'tags3'])
                    ->setCreatedBy($admin)
            ;

            $manager->persist($article);


        }


        $manager->flush();
    }
}
