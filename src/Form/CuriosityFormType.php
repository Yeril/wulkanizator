<?php

    namespace App\Form;

    use App\Entity\Curiosity;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\UrlType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;

    class CuriosityFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('title', TextType::class, [
//                'mapped' => false,
                    'constraints' => [
                        new Length([
                            'min' => 4,
                            'minMessage' => "Wprowadź dłuższy Tytuł",
                            'max' => 25,
                            'maxMessage' => "Wprowadź krótszy Tytuł",
                        ]),
                        new NotBlank([
                            'message' => "Wprowadź wartość"
                        ])
                    ]
                ])
                ->add('text', TextType::class, [
//                'mapped' => false,
                    'constraints' => [
                        new Length([
                            'min' => 4,
                            'minMessage' => "Wprowadź dłuższy tekst",
                            'max' => 40,
                            'maxMessage' => "Wprowadź krótszy tekst",
                        ]),
                        new NotBlank([
                            'message' => "Wprowadź wartość"
                        ])

                    ]
                ])
                ->add('link', UrlType::class, [
//                'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => "Wprowadź wartość"
                        ])
                    ]
                ]);
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => Curiosity::class,
            ]);
        }
    }
