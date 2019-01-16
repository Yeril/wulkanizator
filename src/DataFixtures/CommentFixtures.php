<?php

    namespace App\DataFixtures;

    use App\Entity\Comment;
    use Doctrine\Common\DataFixtures\DependentFixtureInterface;
    use Doctrine\Common\Persistence\ObjectManager;

    class CommentFixtures extends BaseFixture implements DependentFixtureInterface
    {
        public function loadData(ObjectManager $manager)
        {
            $this->createMany(30, 'main_comments', function () {
                $comment = new Comment();
                $comment->setContent(
                    $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true)
                );

                $comment->setUser($this->getRandomReference('main_users'));
                $comment->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));
                $comment->setIsDeleted($this->faker->boolean(20));
                $comment->setArticle($this->getRandomReference('main_articles'));

                return $comment;
            });

            $manager->flush();
        }

        public function getDependencies()
        {
            return [
                ArticleFixtures::class,
                UserFixtures::class,
            ];

        }
    }
