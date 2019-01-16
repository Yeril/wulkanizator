<?php

    namespace App\DataFixtures;

    use App\Entity\User;
    use Doctrine\Common\Persistence\ObjectManager;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

    class UserFixtures extends BaseFixture
    {
        private $passwordEncoder;

        public function __construct(UserPasswordEncoderInterface $passwordEncoder)
        {
            $this->passwordEncoder = $passwordEncoder;
        }

        public function loadData(ObjectManager $manager)
        {
//            foreach ($this->getUserData() as [$email, $password, $firstname, $roles]) {
//                $user = new User();
//                $user->setEmail($email);
//                $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
//                $user->setFirstName($firstname);
//                $user->setRoles($roles);
//                $user->agreeToTerms();
//                $manager->persist($user);
//                $this->setReference($email, $user);
//            }

            $this->createMany(2, 'main_users', function ($i) use ($manager) {
                $user = new User();
                $user->setEmail(sprintf('user%d@user%d.com', $i, $i));
                $user->setFirstName(sprintf('User%d', $i));
                $user->agreeToTerms();
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    sprintf('user%d', $i)
                ));

                return $user;
            });
            $this->createMany(2, 'admin_users', function ($i) {
                $user = new User();
                $user->setEmail(sprintf('admin%d@admin%d.com', $i, $i));
                $user->setFirstName(sprintf('Administrator_no.%d', $i));
                $user->setRoles(['ROLE_ADMIN']);
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
    }
