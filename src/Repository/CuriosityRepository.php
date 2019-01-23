<?php

    namespace App\Repository;

    use App\Entity\Curiosity;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Symfony\Bridge\Doctrine\RegistryInterface;

    /**
     * @method Curiosity|null find($id, $lockMode = null, $lockVersion = null)
     * @method Curiosity|null findOneBy(array $criteria, array $orderBy = null)
     * @method Curiosity[]    findAll()
     * @method Curiosity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class CuriosityRepository extends ServiceEntityRepository
    {
        public function __construct(RegistryInterface $registry)
        {
            parent::__construct($registry, Curiosity::class);
        }


        /**
         * @return Curiosity[]
         */
        public function getAllOrderedByCreatedAtDESC()
        {
            return $this->createQueryBuilder('a')
                ->orderBy('a.createdAt', "DESC")
                ->getQuery()->getResult();
        }

        /**
         * @param $limit
         * @return Curiosity[]
         */
        public function getLimitedOrderedByCreatedAtDESC($limit)
        {
            return $this->createQueryBuilder('a')
                ->orderBy('a.createdAt', "DESC")
                ->setMaxResults($limit)
                ->getQuery()->getResult();
        }

        // /**
        //  * @return Curiosity[] Returns an array of Curiosity objects
        //  */
        /*
        public function findByExampleField($value)
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.exampleField = :val')
                ->setParameter('val', $value)
                ->orderBy('c.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }
        */

        /*
        public function findOneBySomeField($value): ?Curiosity
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.exampleField = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
        */
    }
