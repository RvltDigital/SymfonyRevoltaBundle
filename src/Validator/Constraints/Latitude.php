<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Latitude extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The value is not a latitude.';
}
