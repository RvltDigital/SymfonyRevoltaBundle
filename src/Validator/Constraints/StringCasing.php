<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Doctrine\Common\Annotations\Annotation\Target;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class StringCasing extends Constraint
{
    public const UPPERCASE = 'uppercase';
    public const LOWERCASE = 'lowercase';

    /**
     * @var string
     */
    public $case;

    /**
     * @var string
     */
    public $message = "The value '{{ value }}' is not in {{ case }}";
}
