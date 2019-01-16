<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class StringCasingValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof StringCasing) {
            throw new UnexpectedValueException($constraint, StringCasing::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        switch ($constraint->case) {
            case StringCasing::LOWERCASE:
                $success = strtolower($value) === $value;
                break;
            case StringCasing::UPPERCASE:
                $success = strtoupper($value) === $value;
                break;
            default:
                throw new \InvalidArgumentException("The 'case' property must be one of the " . StringCasing::class . ' constants');
        }

        if (!$success) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setParameter('{{ case }}', $constraint->case)
                ->addViolation();
        }
    }
}
