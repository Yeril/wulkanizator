<?php

    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    class ArticleController extends AbstractController
    {
        /**
         * @Route("/", name="app_homepage")
         */
        public function homepage()
        {
            return $this->render('article/index.html.twig');
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
         */
        public function articles()
        {
            return $this->render('article/articles.html.twig');
        }

        /**
         * @Route("/contact", name="app_contact")
         */
        public function contact()
        {
            return $this->render('article/contact.html.twig');
        }

    }
