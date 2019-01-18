<?php

    namespace App\Controller;

    use App\Entity\Article;
    use App\Repository\ArticleRepository;
    use App\Repository\CuriosityRepository;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    class ArticleController extends AbstractController
    {
        /**
         * @Route("/", name="app_homepage")
         * @param ArticleRepository $articleRepository
         * @param Request $request
         * @param PaginatorInterface $paginator
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function homepage(ArticleRepository $articleRepository, CuriosityRepository $curiosityRepository, Request $request, PaginatorInterface $paginator)
        {
            $query = $articleRepository->findAllPublishedOrderedByNewest();
            $curiosities = $curiosityRepository->getLimitedOrderedByCreatedAtDESC(3);


            $articles = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/

            );
            $articles->setCustomParameters(array(
                'size' => 'small',
                'rounded' => false,
                'span_class' => 'block-warning'
            ));

            return $this->render('article/index.html.twig', [
                'articles' => $articles,
                'curiosities' => $curiosities,
            ]);
        }

        /**
         * @Route("/about", name="app_about")
         */
        public function about()
        {
            return $this->render('article/about.html.twig');
        }

        /**
         * @Route("/articles/{slug}", name="app_articles")
         * @param Article $article
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function articles(Article $article)
        {
            return $this->render('article/articles.html.twig',[
                'article' => $article,
            ]);
        }
    }
