<?php

    namespace App\Controller;

    use App\Entity\Comment;
    use App\Entity\User;
    use App\Entity\UserPhoto;
    use App\Form\UserFormType;
    use App\Repository\ArticleRepository;
    use App\Repository\CommentRepository;
    use App\Repository\UserPhotoRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
    use Symfony\Component\Routing\Annotation\Route;

    class AccountController extends AbstractController
    {
        /**
         * @Route("/account", name="app_account")
         * @IsGranted("ROLE_USER")
         * @param SecurityController $securityController
         * @param ArticleRepository $articleRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function index(SecurityController $securityController, ArticleRepository $articleRepository)
        {
            /** @var User $user */
            $user = $securityController->getUser();

            //clear sql
            $heartCountForUser = $articleRepository->getHeartCountForUID($user->getId());

            $response = $this->render('account/index.html.twig', [
                'user' => $user,
                'heartcount' => $heartCountForUser,
            ]);

            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            return $response;
        }

        /**
         * @Route("/account/avatar", name="app_account_avatar")
         * @param SecurityController $securityController
         * @param UserPhotoRepository $userPhotoRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function avatar(SecurityController $securityController, UserPhotoRepository $userPhotoRepository)
        {
            /** @var User $user */
            $user = $securityController->getUser();
            /** @var UserPhoto[] $avatars */
            $avatars = $userPhotoRepository->getAvatardOrderedByPath();

            return $this->render('account/account.avatar.html.twig', [
                'user' => $user,
                'avatars' => $avatars
            ]);
        }

        /**
         * @Route("/account/avatar/set/{id}/{avatar}", name="app_account_avatar_set")
         * @param SecurityController $securityController
         * @param UserPhotoRepository $userPhotoRepository
         * @param $id
         * @param $avatar
         * @param EntityManagerInterface $entityManager
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function avatar_set(SecurityController $securityController, UserPhotoRepository $userPhotoRepository, $id, $avatar, EntityManagerInterface $entityManager)
        {
            /** @var User $user */
            $user = $securityController->getUser();
            if ($user->getId() != $id) {
                throw new AccessDeniedHttpException("Nie możesz edytować innych użytkowników!");
            }
            $avatar = $userPhotoRepository->find($avatar);
            $user->setPhoto($avatar);
//            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Avatar zaktualizowany!');
            return $this->redirectToRoute('app_account_avatar');
        }

        /**
         * @Route("/account/data", name="app_account_data")
         * @param SecurityController $securityController
         * @param Request $request
         * @param EntityManagerInterface $entityManager
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function data(SecurityController $securityController, Request $request, EntityManagerInterface $entityManager)
        {
            /** @var User $user */
            $user = $securityController->getUser();
            $form = $this->createForm(UserFormType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Zaktualizowano Twoje dane!');
                return $this->redirectToRoute('app_account_data');
            }

            return $this->render('account/account.data.html.twig', [
                'userForm' => $form->createView(),
            ]);
        }

        /**
         * @Route("/account/comments", name="app_account_comments")
         * @param SecurityController $securityController
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function comments(SecurityController $securityController)
        {
            /** @var User $user */
            $user = $securityController->getUser();
            $comments = $user->getNonDeletedComments();

            return $this->render('account/account.comments.html.twig', [
                'comments' => $comments,
            ]);
        }

        /**
         * @Route("/account/comments/delete/{id}", name="app_account_comments_delete")
         * @param SecurityController $securityController
         * @param CommentRepository $commentRepository
         * @param EntityManagerInterface $entityManager
         * @param $id
         * @return \Symfony\Component\HttpFoundation\RedirectResponse
         * @throws \Exception
         */
        public function comments_delete(SecurityController $securityController, CommentRepository $commentRepository, EntityManagerInterface $entityManager, $id)
        {
            /** @var User $user */
            $user = $securityController->getUser();

            /** @var Comment $comments */
            $comment = $commentRepository->find($id);
            //not our comment
            if (!$comment) {
                throw new \Exception("Nie znaleziono takiego komentarza!");
            } else {
                if ($comment->getUser()->getId() != $user->getId()) {
                    throw new AccessDeniedHttpException("Nie twój komentarz, nie buszuj!");
                } else {
                    $comment->setIsDeleted(true);
                    $entityManager->persist($comment);
                    $entityManager->flush();

                    $this->addFlash('success', 'Komentarz został usunięty');
                    return $this->redirectToRoute('app_account_comments');
                }
            }
        }

    }