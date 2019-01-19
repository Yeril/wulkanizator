<?php

    namespace App\DataFixtures;

    use App\Entity\Article;
    use Doctrine\Common\DataFixtures\DependentFixtureInterface;
    use Doctrine\Common\Persistence\ObjectManager;


    class ArticleFixtures extends BaseFixture implements DependentFixtureInterface
    {
        private static $articleTitles = [
            'Tytuł 1',
            'Tytuł 2',
            'Tytuł 3',
        ];

        public function loadData(ObjectManager $manager)
        {
            $this->createMany(10, 'main_articles', function($count) use ($manager) {
                $article = new Article();
                $article->setTitle($this->faker->randomElement(self::$articleTitles))
                    ->setContent($this->faker->text(400));

                // publish most articles
                if ($this->faker->boolean(70)) {
                    $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
                }

                $article = $article->setAuthor($this->getRandomReference('main_users'));
                $article->setHeartCount($this->faker->numberBetween(5, 100))
                ;

                $tags = $this->getRandomReferences('main_tags', $this->faker->numberBetween(0,5));
                foreach ($tags as $tag){
                    $article->addTag($tag);
                }

                return $article;
            });

            $manager->flush();
        }
        public function getDependencies()
        {
            return [
                UserFixtures::class,
                TagFixtures::class,
            ];
        }
    }
