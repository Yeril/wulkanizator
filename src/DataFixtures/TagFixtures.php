<?php

    namespace App\DataFixtures;

    use App\Entity\Tag;
    use Doctrine\Common\Persistence\ObjectManager;

    class TagFixtures extends BaseFixture
    {
        private static $tags = [
            'motoryzacja',
            'auta',
            'naprawa',
            'części',
            'nowości',
            'superpojazdy',
            'wyścigi',
            'policja'
        ];

        public function loadData(ObjectManager $manager)
        {
            $this->createMany(8, 'main_tags', function ($count) {
                $tag = new Tag();
                $tag->setName(TagFixtures::$tags[$count]);
                return $tag;
            });
            $manager->flush();
        }
    }
