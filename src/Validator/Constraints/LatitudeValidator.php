<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LatitudeValidator extends ConstraintValidator
{
    /**
     * @param int|float $value
     * @param Latitude&Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value < -90 || $value > 90) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
