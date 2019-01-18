<?php

    namespace App\Controller;

    use App\Entity\Article;
    use App\Form\ArticleFormType;
    use App\Repository\ArticleRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * Class AdminController
     * @package App\Controller
     * @IsGranted("ROLE_ADMIN")
     */
    class AdminController extends AbstractController
    {
        /**
         * @Route("/admin", name="app_admin")
         */
        public function index()
        {
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminController',
            ]);
        }

        /**
         * @Route("/admin/users", name="app_admin_users")
         */
        public function users()
        {
            return $this->render('admin/users.html.twig', [
            ]);
        }

        /**
         * @Route("/admin/articles", name="app_admin_articles")
         * @param ArticleRepository $articleRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function articles(ArticleRepository $articleRepository)
        {
            $articles = $articleRepository->findAllOrderedByNewest();

            return $this->render('admin/articles.html.twig', [
                'articles' => $articles,
            ]);
        }

        /**
         * @Route("/admin/articles/add/", name="app_admin_article_add")
         * @param Request $request
         * @param EntityManagerInterface $em
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
         */
        public function articles_add(Request $request, EntityManagerInterface $em, SecurityController $securityController)
        {
            $form = $this->createForm(ArticleFormType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var Article $article */
                $article = $form->getData();

                $article->setAuthor($securityController->getUser());
                $article->setHeartCount(5);

                $em->persist($article);
                $em->flush();

                $this->addFlash('success', 'Artykuł dodany!');

                return $this->redirectToRoute('app_admin_articles');
            }

            return $this->render('admin/article_new.html.twig', [
                'articleForm' => $form->createView()
            ]);
        }

        /**
         * @Route("/admin/articles/edit/{id}", name="app_admin_article_edit")
         * @param ArticleRepository $articleRepository
         * @param $id
         * @param Request $request
         * @param EntityManagerInterface $entityManager
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
         */
        public function articles_edit(ArticleRepository $articleRepository, $id, Request $request, EntityManagerInterface $entityManager)
        {
            /** @var Article $article */
            $article = $articleRepository->find($id);
            $form = $this->createForm(ArticleFormType::class, $article);
            // todo: add author to choicelist
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($article);
                $entityManager->flush();

                $this->addFlash('success', 'Artykuł zmodyfikowany!');

                return $this->redirectToRoute('app_admin_articles');
            }

            return $this->render('admin/article_edit.html.twig', [
                'articleForm' => $form->createView()
            ]);
        }

        /**
         * @Route("/admin/articles/delete/{id}", name="app_admin_article_delete")
         * @param ArticleRepository $articleRepository
         * @param $id
         * @param EntityManagerInterface $entityManager
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
         * @Method("DELETE")
         */
        public function articles_delete(ArticleRepository $articleRepository, $id, EntityManagerInterface $entityManager)
        {
            /** @var Article $article */
            $article = $articleRepository->find($id);
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', 'Artykuł usunięty!');

            return $this->redirectToRoute('app_admin_articles');
//            }
//            return $this->render("admin/article_delete.html.twig", [
//                'articleForm' => $form->createView()
//            ]);
        }

        /**
         * @Route("/admin/articles/publish/{id}", name="app_admin_article_publish")
         * @param ArticleRepository $articleRepository
         * @param $id
         * @param Request $request
         * @param EntityManagerInterface $entityManager
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
         * @throws \Exception
         */
        public function articles_publish(ArticleRepository $articleRepository, $id, Request $request, EntityManagerInterface $entityManager)
        {
            $article = $articleRepository->find($id);
            $now = new \DateTime();
            $article->setPublishedAt($now);
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Artykuł opublikowany!');
            return $this->redirectToRoute('app_admin_articles');
        }

        /**
         * @Route("/admin/articles/unpublish/{id}", name="app_admin_article_unpublish")
         * @param ArticleRepository $articleRepository
         * @param $id
         * @param Request $request
         * @param EntityManagerInterface $entityManager
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
         * @throws \Exception
         */
        public function articles_unpublish(ArticleRepository $articleRepository, $id, Request $request, EntityManagerInterface $entityManager)
        {
            $article = $articleRepository->find($id);
            $article->unpublish();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Artykuł został ukryty ze strony!');
            return $this->redirectToRoute('app_admin_articles');
        }

        /**
         * @Route("/admin/comments", name="app_admin_comments")
         */
        public function comments()
        {
            return $this->render('admin/comments.html.twig', [
            ]);
        }

        /**
         * @Route("/admin/tags", name="app_admin_tags")
         */
        public function tags()
        {
            return $this->render('admin/tags.html.twig', [

            ]);
        }
    }
