<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class StringStartsWithValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof StringStartsWith) {
            throw new UnexpectedValueException($constraint, StringStartsWith::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ($constraint->caseInsensitive) {
            $func = 'stripos';
        } else {
            $func = 'strpos';
        }

        if ($func($value, $constraint->value) !== 0) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $this->formatValue($value))
                ->setParameter('{{ value }}', $constraint->value)
                ->addViolation();
        }
    }
}
