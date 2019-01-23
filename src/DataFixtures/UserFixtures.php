<?php

    namespace App\DataFixtures;

    use App\Entity\User;
    use Doctrine\Common\DataFixtures\DependentFixtureInterface;
    use Doctrine\Common\Persistence\ObjectManager;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

    class UserFixtures extends BaseFixture implements DependentFixtureInterface
    {
        private static $names = [
            'Tadeusz',
            'Paweł',
            'Krzysiek',
            'Wojciech',
            'Przemysław',
            'Rafał',
            'Dawid',
            'Michał'
        ];
        private static $lastnames = [
            'Kowal',
            'Gałek',
            'Jasztal',
            'Wójcik',
            'Woźniak',
            'Mazur',
            'Król',
            'Górski'
        ];
        private $passwordEncoder;

        public function __construct(UserPasswordEncoderInterface $passwordEncoder)
        {
            $this->passwordEncoder = $passwordEncoder;
        }

        public function loadData(ObjectManager $manager)
        {
            $this->createMany(7, 'main_users', function ($i) use ($manager) {
                $user = new User();
                $user->setEmail(sprintf('user%d@user%d.com', $i, $i));
                $user->setFirstName(sprintf('User%d', $i));
                $user->agreeToTerms();
                $user->setRoles(['ROLE_USER']);
                $user->setFirstName($this->faker->randomElement(self::$names));
                $user->setLastName($this->faker->randomElement(self::$lastnames));
                /** @noinspection PhpParamsInspection */
                $user->setPhoto($this->getRandomReference('main_photos'));
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    sprintf('user')
                ));

                return $user;
            });
            $this->createMany(2, 'admin_users', function ($i) {
                $user = new User();
                $user->setEmail(sprintf('admin%d@admin%d.com', $i, $i));
                $user->setFirstName(sprintf('Administrator %d', $i));
                $user->setRoles(['ROLE_ADMIN']);
                /** @noinspection PhpParamsInspection */
                $user->setPhoto($this->getRandomReference('main_photos'));
                $user->agreeToTerms();

                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'admin'
                ));

                return $user;
            });
            $manager->flush();
        }

//        private function getUserData(): array
//        {
//            return [
//                ['user@user.com', 'user', 'user', ['ROLE_USER']],
//                ['user1@user1.com', 'user1', 'user1', ['ROLE_USER']],
//                ['admin@admin.com', 'admin', 'Administrator', ['ROLE_ADMIN']],
//            ];
//        }
        public function getDependencies()
        {
            return [
                PhotoFixture::class,
            ];
        }
    }
