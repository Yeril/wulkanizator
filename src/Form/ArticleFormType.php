<?php

    namespace App\Form;

    use App\Entity\Article;
    use App\Repository\TagRepository;
    use App\Repository\UserRepository;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\NotBlank;

    class ArticleFormType extends AbstractType
    {
        private $userRepository;
        /**
         * @var TagRepository
         */
        private $tagRepository;

        public function __construct(UserRepository $userRepository, TagRepository $tagRepository)
        {
            $this->userRepository = $userRepository;
            $this->tagRepository = $tagRepository;
        }

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('title', TextType::class, [
                    'help' => 'Wymyśl coś ciekawego!',
                    'constraints' => [
                        new NotBlank([
                            'message' => "Wprowadź Tytuł"
                        ])
                    ]
                ])
                ->add('content', null, [
                    'constraints' => [
                        new NotBlank([
                            'message' => "Wprowadź Treść"
                        ])
                    ]
                ])
                ->add('publishedAt', null, [
                    'widget' => 'single_text'
                ])
            ;

        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => Article::class,
            ]);
        }
    }