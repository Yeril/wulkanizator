<?php

    namespace App\DataFixtures;

    use App\Entity\UserPhoto;
    use Doctrine\Common\Persistence\ObjectManager;

    class PhotoFixture extends BaseFixture
    {
        public function loadData(ObjectManager $manager)
        {
            $this->createMany(8, 'main_photos', function ($i) {
                $userPhoto = new UserPhoto();
                $userPhoto->setPath(sprintf("img/avatars/avatar0%d.png  ", $i + 2));
                return $userPhoto;
            });
            $manager->flush();
        }
    }
