<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator;

use RvltDigital\SymfonyRevoltaBundle\Interfaces\ValidationInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class ValidatorUtils
{
    public static function check(...$arguments)
    {
        if (in_array(false, array_map(function ($item) {
            /** @var ValidationInterface $item */
            return $item->validate();
        }, self::getValidable($arguments)), true)) {
            throw new ValidatorException();
        }
    }

    /**
     * @param ValidationInterface|ValidationInterface[] ...$arguments
     * @return ValidationInterface[]
     */
    public static function getValidable(...$arguments): array
    {
        $validable = [];
        foreach ($arguments as $item) {
            if (is_iterable($item)) {
                $validable = array_merge($validable, self::getValidable(...$item));
            } elseif ($item instanceof ValidationInterface) {
                $validable[] = $item;
            }
        }
        return $validable;
    }
}
