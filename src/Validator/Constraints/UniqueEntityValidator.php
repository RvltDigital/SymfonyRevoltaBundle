<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Constraints;

use Doctrine\Bundle\DoctrineBundle\Registry;
use RvltDigital\StaticDiBundle\StaticDI;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator as BaseValidator;

class UniqueEntityValidator extends BaseValidator
{
    public function __construct()
    {
        /** @var Registry $doctrineRegistry */
        $doctrineRegistry = StaticDI::get('doctrine');
        parent::__construct($doctrineRegistry);
    }
}
