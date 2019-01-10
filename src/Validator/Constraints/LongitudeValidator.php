<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LongitudeValidator extends ConstraintValidator
{
    /**
     * @param int|float $value
     * @param Constraint&Longitude $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value < -180 || $value > 180) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
