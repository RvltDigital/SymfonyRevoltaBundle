<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class StringStartsWith extends Constraint
{

    /**
     * @var string
     */
    public $value;

    /**
     * @var bool
     */
    public $caseInsensitive = false;

    public $message = "The string '{{ string }}' must start with '{{ value }}'";
}
