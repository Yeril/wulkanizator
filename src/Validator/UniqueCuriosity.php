<?php

    namespace App\Validator;

    use Doctrine\Common\Annotations\Annotation\Target;
    use Symfony\Component\Validator\Constraint;

    /**
     * @Annotation
     * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
     */
    class UniqueCuriosity extends Constraint
    {
        /*
         * Any public properties become valid options for the annotation.
         * Then, use these in your validator class.
         */
        public $message = 'Istnieje już taka ciekawostka!';
    }
