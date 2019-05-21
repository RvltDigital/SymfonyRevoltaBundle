<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator;

use RvltDigital\SymfonyRevoltaBundle\Interfaces\ValidationInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class ValidatorUtils
{
    public static function check(...$arguments)
    {
        $exception = null;
        foreach (self::getValidable($arguments) as $item) {
            if (!$item->validate()) {
                foreach ($item->getErrors() as $error) {
                    $exception = new ValidatorException(
                        sprintf(
                            '[%s] %s: %s',
                            $error->getClass(),
                            $error->getName(),
                            $error->getMessage()
                        ),
                        0,
                        $exception
                    );
                }
            }
        }
        if ($exception !== null) {
            throw $exception;
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
