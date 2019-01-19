<?php

    namespace App\Form;

    use App\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Symfony\Component\Validator\Constraints\NotNull;

    class UserFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('email', EmailType::class, [
                    'empty_data' => '',
                    'required' => true,
                ])
                ->add('firstName', TextType::class, [
                    'empty_data' => '',
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Pole nie może być puste!',
                        ]),
                        new NotNull([
                            'message' => 'Pole nie może być puste!'
                        ])
                    ]
                ])
                ->add('lastName', TextType::class, [
                    'constraints' => [
                        new NotNull([
                            'message' => 'Pole nie może być puste!'
                        ])
                    ]
                ])//todo: generate new password
            ;
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => User::class,
            ]);
        }
    }
