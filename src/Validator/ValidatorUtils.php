<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;
use ReflectionProperty;
use RvltDigital\StaticDiBundle\StaticDI;
use RvltDigital\SymfonyRevoltaBundle\Interfaces\ValidationInterface;
use SplObjectStorage;
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

    public static function getValidableRecursively(...$arguments)
    {
        if (!count($arguments)) {
            return [];
        }
        $doctrine = StaticDI::getEntityManager();
        if (!$arguments[0] instanceof SplObjectStorage) {
            $result = new SplObjectStorage();
        } else {
            $result = array_shift($arguments);
        }
        foreach ($arguments as $item) {
            if (!$item instanceof ValidationInterface) {
                continue;
            }
            $result->attach($item);
            try {
                $metadata = $doctrine->getClassMetadata(get_class($item));
            } catch (Exception $e) {
                continue;
            }
            foreach ($metadata->associationMappings as $name => $config) {
                $class = $config['targetEntity'];
                if (!is_subclass_of($class, ValidationInterface::class)) {
                    continue;
                }
                $reflection = new ReflectionProperty(get_class($item), $name);
                $reflection->setAccessible(true);

                $value = $reflection->getValue($item);
                if ($result->contains($value)) {
                    continue;
                }
                if (
                    $config['type'] & ClassMetadataInfo::MANY_TO_ONE
                    || $config['type'] & ClassMetadataInfo::ONE_TO_ONE
                ) {
                    self::getValidableRecursively($result, $value);
                } else {
                    if (!is_array($value)) {
                        $value = iterator_to_array($value);
                    }
                    self::getValidableRecursively($result, ...$value);
                }
            }
        }

        return self::getValidable(...iterator_to_array($result));
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
