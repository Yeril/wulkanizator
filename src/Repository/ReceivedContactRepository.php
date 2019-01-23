<?php

    namespace App\Repository;

    use App\Entity\ReceivedContact;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\RegistryInterface;

    /**
     * @method ReceivedContact|null find($id, $lockMode = null, $lockVersion = null)
     * @method ReceivedContact|null findOneBy(array $criteria, array $orderBy = null)
     * @method ReceivedContact[]    findAll()
     * @method ReceivedContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class ReceivedContactRepository extends ServiceEntityRepository
    {
        public function __construct(RegistryInterface $registry)
        {
            parent::__construct($registry, ReceivedContact::class);
        }

        /**
         * @return ReceivedContact[]
         */
        public function findAllOrderedByEmail()
        {
            return $this->createQueryBuilder('a')
                ->orderBy('a.email', 'ASC')
                ->getQuery()
                ->getResult();
        }

        /**
         * @return boolean
         */
        public function queryReturnedEmptySet()
        {
            if (true === empty($this->findAll())) {
                return true;
            }
            return false;
        }

        // /**
        //  * @return ReceivedContact[] Returns an array of ReceivedContact objects
        //  */
        /*
        public function findByExampleField($value)
        {
            return $this->createQueryBuilder('r')
                ->andWhere('r.exampleField = :val')
                ->setParameter('val', $value)
                ->orderBy('r.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }
        */

        /*
        public function findOneBySomeField($value): ?ReceivedContact
        {
            return $this->createQueryBuilder('r')
                ->andWhere('r.exampleField = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
        */
    }
