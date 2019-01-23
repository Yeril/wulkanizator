<?php

    namespace App\Validator;

    use App\Repository\CuriosityRepository;
    use Symfony\Component\Validator\Constraint;
    use Symfony\Component\Validator\ConstraintValidator;

    class UniqueCuriosityValidator extends ConstraintValidator
    {
        private $curiosityRepository;

        public function __construct(CuriosityRepository $curiosityRepository)
        {
            $this->curiosityRepository = $curiosityRepository;
        }

        public function validate($value, Constraint $constraint)
        {
            /* @var $constraint \App\Validator\UniqueCuriosity */

            $existingCuriosity = $this->curiosityRepository->findOneBy([
                'link' => $value
            ]);
            $existingCuriosity2 = $this->curiosityRepository->findOneBy([
                'title' => $value
            ]);
            $existingCuriosity3 = $this->curiosityRepository->findOneBy([
                'text' => $value
            ]);

            if (!$existingCuriosity && !$existingCuriosity3 && !$existingCuriosity2) {
                return;
            }

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
