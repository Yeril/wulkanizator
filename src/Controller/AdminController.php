<?php

    namespace App\Controller;

    use App\Entity\Article;
    use App\Entity\Comment;
    use App\Entity\Tag;
    use App\Entity\User;
    use App\Entity\UserPhoto;
    use App\Form\ArticleFormType;
    use App\Form\TagFormType;
    use App\Form\UserFormType;
    use App\Repository\ArticleRepository;
    use App\Repository\CommentRepository;
    use App\Repository\TagRepository;
    use App\Repository\UserPhotoRepository;
    use App\Repository\UserRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Knp\Component\Pager\PaginatorInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
        public function index(SecurityController $securityController)
        {
            /** @var User $user */
            $user = $securityController->getUser();
            return $this->render('admin/index.html.twig', [
                'user' => $user,
            ]);
        }

        /**
         * @Route("/admin/users", name="app_admin_users")
         * @param UserRepository $userRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function users(UserRepository $userRepository)
        {
            $users = $userRepository->findAllWithPhotos();
            return $this->render('admin/users/users.html.twig', [
                'users' => $users
            ]);
        }

        /**
         * @Route("/admin/users/edit/{id}", name="app_admin_users_edit")
         * @param UserRepository $userRepository
         * @param $id
         * @param EntityManagerInterface $entityManager
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function users_edit(UserRepository $userRepository, $id, EntityManagerInterface $entityManager, Request $request)
        {
            /** @var User $user */
            $user = $userRepository->find($id);
            $form = $this->createForm(UserFormType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Użytkownik zmodyfikowany!');
                return $this->redirectToRoute('app_admin_users');
            }

            return $this->render('admin/users/users.edit.html.twig', [
                'userForm' => $form->createView()
            ]);
        }

        /**
         * @Route("/admin/users/delete/{id}", name="app_admin_users_delete")
         * @param UserRepository $userRepository
         * @param $id
         * @param EntityManagerInterface $entityManager
         * @param Request $request
         * @param ArticleRepository $articleRepository
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         * @Method("DELETE")
         */
        public function users_delete(UserRepository $userRepository, $id, EntityManagerInterface $entityManager, Request $request, ArticleRepository $articleRepository, SecurityController $securityController)
        {

            /** @var User $user */
            $user = $userRepository->find($id);
            if ($user === $securityController->getUser()) {
                throw new AccessDeniedHttpException("You cannot delete yourself!");
            }
//            if we delete user with articles his article becomes ours
            /** @var Article[] $userArticles */
            $userArticles = $articleRepository->findAllofUser($user);
            foreach ($userArticles as $article) {
                $article->setAuthor($securityController->getUser());
                $entityManager->persist($article);
            }
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Użytkownik usunięty!');

            return $this->redirectToRoute('app_admin_users');
        }

        /**
         * @Route("/admin/users/comments/{id}", name="app_admin_users_comments")
         * @param UserRepository $userRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function users_comments(UserRepository $userRepository, $id, CommentRepository $commentRepository)
        {
            /** @var User $user */
            $user = $userRepository->find($id);

            return $this->render('admin/users/user.comment.html.twig', [
                'user' => $user
            ]);
        }

        /**
         * @Route("/admin/users/details/{id}", name="app_admin_users_details")
         * @param UserRepository $userRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function users_details(UserRepository $userRepository, $id, PaginatorInterface $paginator, Request $request)
        {
            /** @var User $user */
            $user = $userRepository->find($id);

            $articles = $paginator->paginate(
                $user->getArticles(), /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/

            );

            $articles->setCustomParameters(array(
                'size' => 'small',
                'rounded' => false,
                'span_class' => 'block-warning'
            ));

            $comments = $paginator->paginate(
                $user->getComments(), /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/

            );

            $comments->setCustomParameters(array(
                'size' => 'small',
                'rounded' => false,
                'span_class' => 'block-warning'
            ));

            return $this->render('admin/users/user.details.html.twig', [
                'user' => $user,
                'articles' => $articles,
                'comments' => $comments
            ]);
        }

        /**
         * @Route("/admin/users/avatar/{id}", name="app_admin_users_avatar")
         * @param UserRepository $userRepository
         * @param $id
         * @param UserPhotoRepository $userPhotoRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function users_avatar(UserRepository $userRepository, $id, UserPhotoRepository $userPhotoRepository)
        {
            /** @var User $user */
            $user = $userRepository->find($id);
            /** @var UserPhoto[] $avatars */
            $avatars = $userPhotoRepository->getAvatardOrderedByPath();

            return $this->render('admin/users/user.avatar.html.twig', [
                'user' => $user,
                'avatars' => $avatars,
            ]);
        }

        /**
         * @Route("/admin/users/avatar/{avatar}/{id}", name="app_admin_users_avatar_set")
         * @param UserRepository $userRepository
         * @param $id
         * @param UserPhotoRepository $userPhotoRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function users_set_avatar(UserRepository $userRepository, $id, UserPhotoRepository $userPhotoRepository, $avatar, EntityManagerInterface $entityManager)
        {
            /** @var User $user */
            $user = $userRepository->find($id);
            /** @var UserPhoto[] $avatars */
            $avatar = $userPhotoRepository->find($avatar);

            $user->setPhoto($avatar);
//            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Avatar zaktualizowany!');
            return $this->redirectToRoute('app_admin_users_avatar', array('id' => $id));
        }

        /**
         * @Route("/admin/users/comments/delete/{id}", name="app_admin_users_comments_delete")
         * @param CommentRepository $commentRepository
         * @param $id
         * @param EntityManagerInterface $entityManager
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function users_comments_delete(CommentRepository $commentRepository, $id, EntityManagerInterface $entityManager)
        {
            /** @var User $user */
            $comment = $commentRepository->find($id);
            $userid = $comment->getUser()->getId();
            $comment->setIsDeleted(true);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_users_comments', ['id' => $userid]);
        }

        /**
         * @Route("/admin/articles", name="app_admin_articles")
         * @param ArticleRepository $articleRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function articles(ArticleRepository $articleRepository)
        {
            /** @var Article[] $articles */
            $articles = $articleRepository->findAllOrderedByNewest();

            return $this->render('admin/article/articles.html.twig', [
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

            return $this->render('admin/article/article_new.html.twig', [
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
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
//                $tags = $form['tags']->getData();
////                dd($tags);
//                foreach ($tags as $tag) {
//                    dump($tag);
//                }
//                $article->clearTags();
//                $article->setTags($tags);


                $entityManager->persist($article);
                $entityManager->flush();

                $this->addFlash('success', 'Artykuł zmodyfikowany!');

//                $article = $articleRepository->find($id);
//                $form = $this->createForm(ArticleFormType::class, $article);
                return $this->redirectToRoute('app_admin_articles');

            }

            return $this->render('admin/article/article_edit.html.twig', [
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
         * @Route("/admin/articles/comments/{id}", name="app_admin_article_comments")
         * @param ArticleRepository $articleRepository
         * @param $id
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
         */
        public function articles_comments(ArticleRepository $articleRepository, $id, Request $request, CommentRepository $commentRepository, PaginatorInterface $paginator)
        {
            /** @var Article $article */
            $article = $articleRepository->find($id);
            /** @var Comment[] $comments */
            $comments = $article->getComments();

            $comments_pag = $paginator->paginate(
                $comments, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/

            );

            $comments_pag->setCustomParameters(array(
                'size' => 'small',
                'rounded' => false,
                'span_class' => 'block-warning'
            ));

            return $this->render('admin/article/article_comments.html.twig', [
                'article' => $article,
                'comments' => $comments_pag,
            ]);
        }

        /**
         * @Route("/admin/comments", name="app_admin_comments")
         */
        public function comments(CommentRepository $commentRepository)
        {
            /** @var Comment[] $comments */
            $comments = $commentRepository->findAll();
            return $this->render('admin/comments/comments.html.twig', [
                'comments' => $comments,
            ]);
        }

        /**
         * @Route("/admin/comments/show/{id}", name="app_admin_comment_show")
         * @param CommentRepository $commentRepository
         * @param $id
         * @return Response
         */
        public function comments_show(CommentRepository $commentRepository, $id)
        {
            /** @var Comment[] $comments */
            $comment = $commentRepository->find($id);
            return $this->render('admin/comments/comment.show.html.twig', [
                'comment' => $comment,
            ]);
        }

        /**
         * @Route("/admin/comments/delete/{slug}", name="app_admin_comment_delete")
         * @param CommentRepository $commentRepository
         * @param Request $request
         * @param EntityManagerInterface $entityManager
         * @param $slug
         * @return string|Response
         */
        public function comments_delete(CommentRepository $commentRepository, Request $request, EntityManagerInterface $entityManager, $slug)
        {
            try {
                $id = $request->request->getInt('id');
                /** @var Comment[] $comments */
                $comment = $commentRepository->find($id);
                if ($comment == null) {
                    return new Response("fail");
                }
                if ($slug == 'delete') {
                    $comment->setIsDeleted(1);
                    $entityManager->persist($comment);
                } elseif ($slug == 'create') {
                    $comment->setIsDeleted(0);
                    $entityManager->persist($comment);
                } elseif ($slug == 'permamently') {
                    $entityManager->remove($comment);
                } else
                    return new Response("fail");

                $entityManager->flush();
                if ($slug == 'permamently')
                    $this->redirectToRoute('app_admin_comments');
                return new Response("success");
            } catch (\Exception $e) {
                return $e ? $e->getMessage() : new Response("fail");
            }
        }

        /**
         * @Route("/admin/tags", name="app_admin_tags")
         */
        public function tags(TagRepository $tagRepository)
        {
            /** @var Tag[] $tags */
            $tags = $tagRepository->findAllOrderedByCreatedAt();
            return $this->render('admin/tags/tags.html.twig', [
                'tags' => $tags
            ]);
        }

        /**
         * @Route("/admin/tags/show/{id}", name="app_admin_tag_show")
         */
        public function tags_show(TagRepository $tagRepository, $id)
        {
            /** @var Tag[] $tags */
            $tag = $tagRepository->find($id);
            dd($tag);

            return $this->render('admin/tags/tag_show.html.twig', [
                'tag' => $tag,
            ]);
        }

        /**
         * @Route("/admin/tags/add", name="app_admin_tag_add")
         */
        public function tags_add(Request $request, EntityManagerInterface $entityManager)
        {
            $form = $this->createForm(TagFormType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var Article $article */
                $tag = $form->getData();

                $entityManager->persist($tag);
                $entityManager->flush();

                $this->addFlash('success', 'Tag dodany!');

                return $this->redirectToRoute('app_admin_tags');
            }


            return $this->render('admin/tags/tag_add.html.twig', [
                'tagForm' => $form->createView(),
            ]);
        }

        /**
         * @Route("/admin/tags/edit/{id}", name="app_admin_tag_edit")
         */
        public function tags_edit(TagRepository $tagRepository, $id, EntityManagerInterface $entityManager, Request $request)
        {
            /** @var Tag $tag */
            $tag = $tagRepository->find($id);
            $form = $this->createForm(TagFormType::class, $tag);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($tag);
                $entityManager->flush();

                $this->addFlash('success', 'Tag zmodyfikowany!');

                return $this->redirectToRoute('app_admin_tags');
            }
            return $this->render('admin/tags/tag_edit.html.twig', [
                'tagForm' => $form->createView(),
            ]);
        }

        /**
         * @Route("/admin/tags/delete/{id}", name="app_admin_tag_delete")
         */
        public function tags_delete(TagRepository $tagRepository, $id, EntityManagerInterface $entityManager)
        {
            $tag = $tagRepository->find($id);
            if ($tag) {
                $entityManager->remove($tag);
                $entityManager->flush();

                $this->addFlash('success', 'Tag usunięty!');
                return $this->redirectToRoute('app_admin_tags');
            }
            return $this->redirectToRoute('app_admin_tags');
        }
    }
