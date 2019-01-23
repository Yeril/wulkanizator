<?php

    namespace App\DataFixtures;

    use App\Entity\Curiosity;
    use Doctrine\Common\Persistence\ObjectManager;

    class CuriosityFixtures extends BaseFixture
    {
        static $vars = [
            ['Bugatti Chiron… z klocków LEGO', 'Co można zbudować z klocków LEGO? Wszystko! Nawet sportowy samochód w naturalnej skali. ', 'https://www.motofakty.pl/artykul/bugatti-chiron-z-klockow-lego.html'],
            ['McLaren Senna. Na 1 tonę masy pojazdu przypada 668 KM mocy!', 'Nie było i nie będzie drugiego takiego samochodu. Zastrzeżono nazwę, a produkcję ograniczono do 500 sztuk.', 'https://www.motofakty.pl/artykul/mclaren-senna-na-1-tone-masy-pojazdu-przypada-668-km-mocy.html'],
            ['Koenigsegg. Szwedzki pomysł na superauta', 'Wśród wielu talentów Christiana von Koenigsegga jest dar opowiadania.', 'https://www.motofakty.pl/artykul/koenigsegg-szwedzki-pomysl-na-superauta.html'],
            ['360c Volvo Cars. Koncept pojazdu autonomicznego', 'Czy można wyobrazić sobie świat, w którym długie podróże można odbywać bez konieczności odwiedzania lotnisk.', 'https://www.motofakty.pl/artykul/360c-volvo-cars-koncept-pojazdu-autonomicznego.html']
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
