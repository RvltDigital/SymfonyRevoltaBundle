<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Doctrine\Common\Annotations\Annotation\Target;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class State extends Constraint
{
    public $class = '';
    public $message = 'Transition from state "{{ originalState }}" to "{{ newState }}" is not valid';

    public function getTargets()
    {
        return [
            static::CLASS_CONSTRAINT,
        ];
    }
}
