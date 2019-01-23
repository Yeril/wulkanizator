<?php

    namespace App\Repository;

    use App\Entity\Comment;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Common\Collections\Criteria;
    use Doctrine\ORM\QueryBuilder;
    use Symfony\Bridge\Doctrine\RegistryInterface;

    /**
     * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
     * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
     * @method Comment[]    findAll()
     * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class CommentRepository extends ServiceEntityRepository
    {
        public function __construct(RegistryInterface $registry)
        {
            parent::__construct($registry, Comment::class);
        }

        public static function createNonDeletedCriteria(): Criteria
        {
            return Criteria::create()
                ->andWhere(Criteria::expr()->eq('isDeleted', false))
                ->orderBy(['createdAt' => 'DESC']);
        }

        public function getCommentsFromArticleId($id)
        {
            return $this->createQueryBuilder('a')
                ->leftJoin('a.article', 'article')
                ->where('article.id=:id')->setParameter('id', $id)
                ->andWhere('a.isDeleted=0')
                ->getQuery()->getResult();
        }


        /**
         * @param string|null $term
         * @return QueryBuilder
         */
        public function getWithSearchQueryBuilder(?string $term): QueryBuilder
        {
            $qb = $this->createQueryBuilder('c')
                ->innerJoin('c.article', 'a')
                ->addSelect('a');

            if ($term) {
                $qb->andWhere('c.content LIKE :term OR c.authorName LIKE :term OR a.title LIKE :term')
                    ->setParameter('term', '%' . $term . '%');
            }

            return $qb
                ->orderBy('c.createdAt', 'DESC');
        }

        public function findAllofUser(\App\Entity\User $user)
        {
            return $this->createQueryBuilder('a')
                ->orderBy('a.createdAt', 'DESC')
                ->andWhere('a.user=:user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
        }

    }
