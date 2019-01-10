<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UrlPath extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The url path is not valid.';
}
