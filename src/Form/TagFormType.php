<?php

    namespace App\Form;

    use App\Entity\Article;
    use App\Entity\Tag;
    use App\Repository\ArticleRepository;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class TagFormType extends AbstractType
    {

        /**
         * @var ArticleRepository
         */
        private $repository;

        public function __construct(ArticleRepository $repository)
        {

            $this->repository = $repository;
        }

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('name', TextType::class, [
                    'empty_data' => '',
                    'required' => true,
                ])
                ->add('article', EntityType::class, [
                    'class' => Article::class,
                    'choice_label' => function (Article $article) {
                        return sprintf('id: %d,   title:"%s",   %s', $article->getId(), $article->getTitle(), $article->getSlug());
                    },
                    'multiple' => true,
                    'expanded' => true,
                ]);
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => Tag::class,
            ]);
        }
    }
