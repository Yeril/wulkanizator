<?php

    namespace App\Form;

    use App\Entity\Comment;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;

    class CommentFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('content', TextareaType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Wprowadź wiadomość!'
                        ]),
                        new Length([
                            'min' => 3,
                            'minMessage' => 'Musisz wprowadzić więcej znaków do komentarza!',
                            'max' => 500,
                            'maxMessage' => 'Przekroczyłeś/aś limit znaków (500)!'
                        ])
                    ]
                ]);;
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => Comment::class,
            ]);
        }
    }
