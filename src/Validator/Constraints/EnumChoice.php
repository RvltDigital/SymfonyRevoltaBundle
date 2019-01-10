<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EnumChoice extends Constraint
{
    /**
     * @var string
     */
    public $enum;

    /**
     * @var string
     */
    public $message = 'The value is not a valid choice.';
}
