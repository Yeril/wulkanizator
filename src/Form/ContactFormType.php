<?php

    namespace App\Form;

    use App\Entity\ReceivedContact;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;

    class ContactFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('email', EmailType::class)
                // don't use password: avoid EVER setting that on a
                // field that might be persisted
                ->add('content', TextareaType::class, [
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Wprowadź wiadomość!'
                        ]),
                        new Length([
                            'min' => 20,
                            'minMessage' => 'Opisz swój problem przynajmniej w kliku zdaniach!',
                            'max' => 500,
                            'maxMessage' => 'Przekroczyłeś/aś limit znaków (500)!'
                        ])
                    ]
                ]);
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => ReceivedContact::class,
            ]);
        }
    }
