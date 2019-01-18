<?php

    namespace App\DataFixtures;

    use App\Entity\Curiosity;
    use Doctrine\Common\Persistence\ObjectManager;

    class CuriosityFixtures extends BaseFixture
    {
        static $vars = [
            ['Auto ma 45 kół!', 'Naukowcy odkrywają!', 'https://googlee.com'],
            ['Auto ma 3 koła!', 'Pierwszy polski trójnóg!', 'https://googleee.com'],
            ['Auto ma 2 koła!', 'Rower', 'https://google.com'],
        ];

        public function loadData(ObjectManager $manager)
        {
            foreach (self::$vars as [$title, $text, $href]) {
                $curiosity = new Curiosity();
                $curiosity->setTitle($title);
                $curiosity->setLink($href);
                $curiosity->setText($text);
                $manager->persist($curiosity);
            }

            $manager->flush();
        }
    }
