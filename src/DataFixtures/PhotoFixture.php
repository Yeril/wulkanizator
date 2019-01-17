<?php

    namespace App\DataFixtures;

    use App\Entity\UserPhoto;
    use Doctrine\Common\Persistence\ObjectManager;

    class PhotoFixture extends BaseFixture
    {
        public function loadData(ObjectManager $manager)
        {
            $this->createMany(9, 'main_photos', function ($i) {
                $userPhoto = new UserPhoto();
                $userPhoto->setPath(sprintf("img/avatars/avatar0%d.png  ", $i + 1));
                return $userPhoto;
            });
            $manager->flush();
        }
    }
