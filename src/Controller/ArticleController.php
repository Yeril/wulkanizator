<?php

    namespace App\Controller;

    use App\Entity\Article;
    use App\Repository\ArticleRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    class ArticleController extends AbstractController
    {
        /**
         * @Route("/", name="app_homepage")
         * @param ArticleRepository $repository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function homepage(ArticleRepository $repository)
        {
            $articles = $repository->findAllPublishedOrderedByNewest();

            return $this->render('article/index.html.twig', [
                'articles' => $articles,
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

        /**
         * @Route("/contact", name="app_contact")
         */
        public function contact()
        {
            return $this->render('article/contact.html.twig');
        }

    }
