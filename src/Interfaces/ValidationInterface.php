<?php

namespace RvltDigital\SymfonyRevoltaBundle\Interfaces;

use RvltDigital\SymfonyRevoltaBundle\Validator\Error\ErrorStack;

interface ValidationInterface
{
    public function validate(): bool;

    public function getErrors(): ErrorStack;
}
