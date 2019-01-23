<?php

    namespace App\DataFixtures;

    use App\Entity\Article;
    use Doctrine\Common\DataFixtures\DependentFixtureInterface;
    use Doctrine\Common\Persistence\ObjectManager;


    class ArticleFixtures extends BaseFixture implements DependentFixtureInterface
    {
        private static $articleTitles = [
            'Co pod choinkę? Pomysły na motoryzacyjny prezent dla faceta i dziewczyny',
            'Poradnik: Jak przygotować auto do zimy?',
            'Powłoki ceramiczne - ratunek dla miękkich lakierów czy wielka ściema?',
            'Mali i średni chcą standardu Premium!',
            'Detailing dla każdego, czyli jak tanim kosztem samemu zadbać o samochód',
            '10 największych błędów kierowców',
            'Jak działa podpora wału napędowego?',
            'Kalkulator leasingu – sprawdź, ile zapłacisz za auto',
            'Regeneracja tylnej belki – co warto wiedzieć o regeneracji tylnego zawieszenia?',
            'Manualne i elektroniczne. Opłaty drogowe w Polsce',
            'Jak naprawić wgniecenie w aucie? Sposoby na bezinwazyjne usuwanie wgnieceń w samochodzie',
            'Masz Skodę? Masz również tanie OC,',
            ' Jak znaleźć dobry serwis samochodowy?',
            'Oszczędzać trzeba umieć',
            'Zadbaj o opony w najlepszy możliwy sposób!',
            'Samochód zastępczy – garść informacji',
            'Pamiętaj o tym, gdy wypożyczasz auto!',
            'Toyota Yaris w rajdach WRC – osiągnięcia',
            'Nowa seria VW Transporterów – przestrzeń i moc',
            'Dlaczego warto kupować części zamienne przez Internet?',
            'Dlaczego warto kupować części zamienne przez Internet?',
        ];

        public function loadData(ObjectManager $manager)
        {
            $this->createMany(20, 'main_articles', function ($count) use ($manager) {
                $article = new Article();
                $article->setTitle(self::$articleTitles[$count])
                    ->setContent($this->faker->text(3000));

                // publish most articles
                if ($this->faker->boolean(70)) {
                    $article->setPublishedAt($this->faker->dateTimeBetween('-60 days', '-6 days'));
                }

                $article = $article->setAuthor($this->getRandomReference('main_users'));
                $article->setHeartCount($this->faker->numberBetween(5, 30));

                $tags = $this->getRandomReferences('main_tags', $this->faker->numberBetween(0, 5));
                foreach ($tags as $tag) {
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
