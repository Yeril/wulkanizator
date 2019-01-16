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
            foreach ($this->getUserData() as [$email, $password, $firstname, $roles]) {
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
                $user->setFirstName($firstname);
                $user->setRoles($roles);
                $manager->persist($user);
                $this->setReference($email, $user);
            }
            $manager->flush();
        }

        private function getUserData(): array
        {
            return [
                ['user@user.com', 'user', 'user', ['ROLE_USER']],
                ['user1@user1.com', 'user1', 'user1', ['ROLE_USER']],
                ['admin@admin.com', 'admin', 'Administrator', ['ROLE_ADMIN']],
            ];
        }
    }
