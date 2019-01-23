<?php

    namespace App\Repository;

    use App\Entity\Article;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\DBAL\DBALException;
    use Doctrine\ORM\QueryBuilder;
    use Symfony\Bridge\Doctrine\RegistryInterface;

    /**
     * @method Article|null find($id, $lockMode = null, $lockVersion = null)
     * @method Article|null findOneBy(array $criteria, array $orderBy = null)
     * @method Article[]    findAll()
     * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class ArticleRepository extends ServiceEntityRepository
    {
        public function __construct(RegistryInterface $registry)
        {
            parent::__construct($registry, Article::class);
        }

        public function getHeartCountForUID($id)
        {
            $conn = $this->getEntityManager()->getConnection();
            $sql = 'select sum(article.heart_count) sum from article left join user u on article.author_id = u.id where u.id=:id;';
            try {
                $stmt = $conn->prepare($sql);
            } catch (DBALException $e) {
            }
            $stmt->execute(['id' => $id]);

            return $stmt->fetchAll()[0]['sum'];
        }

        /**
         * @return Article[]
         * @throws \Exception
         */
        public function findAllPublishedOrderedByNewest()
        {
            $now = new \DateTime();
            return $this->addIsPublishedQueryBuilder()
                ->orderBy('a.publishedAt', 'DESC')
                ->andWhere('a.publishedAt<=:param')
                ->setParameter('param', $now)
                ->getQuery()
                ->getResult();
        }

        public function findAllOrderedByNewest()
        {
            return $this->createQueryBuilder('a')
                ->orderBy('a.publishedAt', 'DESC')
                ->getQuery()
                ->getResult();
        }

        public function findAllofUser($user)
        {
            return $this->createQueryBuilder('a')
                ->orderBy('a.publishedAt', 'DESC')
                ->andWhere('a.author=:user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
        }

        /*
        public function findOneBySomeField($value): ?Article
        {
            return $this->createQueryBuilder('a')
                ->andWhere('a.exampleField = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
        */

        private function addIsPublishedQueryBuilder(QueryBuilder $qb = null)
        {
            return $this->getOrCreateQueryBuilder($qb)
                ->andWhere('a.publishedAt IS NOT NULL');
        }

        private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
        {
            return $qb ?: $this->createQueryBuilder('a');
        }

        public function findAllofTag($tag)
        {
            return $this->createQueryBuilder('a')
                ->leftJoin('a.tags', 'tags')
                ->andWhere('tags.id=:tag')
                ->setParameter('tag', $tag)
                ->orderBy('a.publishedAt', 'DESC')
                ->getQuery()
                ->getResult();
        }
    }
