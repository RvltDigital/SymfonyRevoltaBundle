<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use RvltDigital\SymfonyRevoltaBundle\Interfaces\StateInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StateValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        assert($constraint instanceof State);

        if (!$constraint->class) {
            throw new \LogicException('The class attribute must be set');
        }

        $class = $constraint->class;
        $state = new $class($value);
        if (!$state instanceof StateInterface) {
            throw new \LogicException('The state class must be instance of ' . StateInterface::class);
        }

        if (!$state->isTransitionAllowed()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ originalState }}', $state->getStoredState())
                ->setParameter('{{ newState }}', $state->getCurrentState())
                ->addViolation();
        }
    }
}
