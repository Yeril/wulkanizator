<?php

    namespace App\Controller;

    use App\Entity\Article;
    use App\Entity\Comment;
    use App\Form\CommentFormType;
    use App\Repository\ArticleRepository;
    use App\Repository\CommentRepository;
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
         * @param CuriosityRepository $curiosityRepository
         * @param Request $request
         * @param PaginatorInterface $paginator
         * @return \Symfony\Component\HttpFoundation\Response
         * @throws \Exception
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
         * @param Request $request
         * @param Article $article
         * @param CommentRepository $commentRepository
         * @param $slug
         * @param SecurityController $securityController
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function articles(Request $request, Article $article, CommentRepository $commentRepository, $slug, SecurityController $securityController)
        {
            /** @var Comment[] $comments */
            $comments = $commentRepository->getCommentsFromArticleId($article->getId());
            $comment = new Comment();
            $form = $this->createForm(CommentFormType::class, $comment);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if (!$this->isGranted("ROLE_USER")) {
                    $this->addFlash('success', 'Najpierw się zaloguj!');
                    return $this->redirectToRoute('app_login');
                }

                $comment->setContent($form->get('content')->getData());
                $comment->setIsDeleted(false);
                $user = $securityController->getUser();
                $comment->setUser($user);
                $comment->setArticle($article);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($comment);
                $entityManager->flush();

                unset($form);
                unset($comment);

                $this->addFlash('success', 'Komentarz został wysłany');
                return $this->redirectToRoute('app_articles', ['slug' => $slug]);
            }
            /** @var Article $article */
            /** @var Comment[] $comments */
            return $this->render('article/articles.html.twig', [
                'article' => $article,
                'comments' => $comments,
                'commentForm' => $form->createView(),
            ]);
        }
    }
