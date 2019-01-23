<?php

    namespace App\Repository;

    use App\Entity\UserPhoto;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bridge\Doctrine\RegistryInterface;

    /**
     * @method UserPhoto|null find($id, $lockMode = null, $lockVersion = null)
     * @method UserPhoto|null findOneBy(array $criteria, array $orderBy = null)
     * @method UserPhoto[]    findAll()
     * @method UserPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class UserPhotoRepository extends ServiceEntityRepository
    {
        /**
         * @var EntityManagerInterface
         */
        private $entityManager;

        public function __construct(RegistryInterface $registry, EntityManagerInterface $entityManager)
        {
            parent::__construct($registry, UserPhoto::class);
            $this->entityManager = $entityManager;
        }

        public function getDefaultPhotoForRegisteredUser()
        {
            $result = $this->findOneBy(['path' => 'img/avatars/avatar01.png']);
            if ($result) {
                return $result->getPath();
            } else {
                $photo = new UserPhoto();
                $photo->setPath('img/avatars/avatar01.png');
                $this->entityManager->persist($photo);
                $this->entityManager->flush();
                return $photo->getPath();
            }
        }

        public function getAvatardOrderedByPath()
        {
            return $this->createQueryBuilder('a')
                ->orderBy('a.path', 'ASC')
                ->getQuery()
                ->getResult();
        }

        // /**
        //  * @return UserPhoto[] Returns an array of UserPhoto objects
        //  */
        /*
        public function findByExampleField($value)
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.exampleField = :val')
                ->setParameter('val', $value)
                ->orderBy('u.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }
        */

        /*
        public function findOneBySomeField($value): ?UserPhoto
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.exampleField = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
        */
    }
