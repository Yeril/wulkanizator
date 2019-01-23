<?php

    namespace App\Controller;

    use App\Entity\Article;
    use App\Entity\Tag;
    use App\Repository\ArticleRepository;
    use App\Repository\TagRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    class TagController extends AbstractController
    {
        /**
         * @Route("/tags/{id}", name="app_tags")
         * @param TagRepository $tagRepository
         * @param $id
         * @param ArticleRepository $articleRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function index(TagRepository $tagRepository, $id, ArticleRepository $articleRepository)
        {
            /** @var Tag $tags */
            $tag = $tagRepository->find($id);
            /** @var Article[] $articles */
            $articles = $articleRepository->findAllofTag($tag->getId());

            return $this->render('tag/index.html.twig', [
                'tag' => $tag,
                'articles' => $articles,
            ]);
        }
    }
