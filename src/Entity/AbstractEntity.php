<?php

namespace RvltDigital\SymfonyRevoltaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RvltDigital\StaticDiBundle\StaticDI;
use RvltDigital\SymfonyRevoltaBundle\Traits\DataSetterTrait;
use RvltDigital\SymfonyRevoltaBundle\Validator\Error\Error;
use RvltDigital\SymfonyRevoltaBundle\Validator\Error\ErrorStack;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validation;

/**
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractEntity
{
    use DataSetterTrait;

    /**
     * @var ErrorStack
     */
    protected $errors;

    public function __construct()
    {
        $this->setErrors();
    }

    abstract public function getId(): ?int;

    public function validate(): bool
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        foreach ($validator->validate($this) as $violation) {
            $this->errors->addError(
                new Error(
                    $violation->getMessage(),
                    $violation->getPropertyPath(),
                    get_class($this)
                )
            );
        }
        if (!$this->errors->empty()) {
            return false;
        }
        return true;
    }

    /**
     * @ORM\PreFlush()
     */
    public function preFlushValidation()
    {
        if (!$this->errors->empty() || !$this->validate()) {
            throw new ValidatorException();
        }
    }

    /**
     * @ORM\PostLoad()
     */
    public function postLoad()
    {
        $this->setErrors();
    }

    protected function setErrors(ErrorStack $errors = null)
    {
        $this->errors = $errors ?? new ErrorStack();
    }

    public function getErrors(string $name = null): ErrorStack
    {
        return $name === null ? $this->errors : $this->errors->filter($name);
    }

    public function save(bool $flushAll = true)
    {
        $em = StaticDI::getEntityManager();
        $em->persist($this);
        $em->flush($flushAll ? null : $this);
    }

    public function delete()
    {
        $em = StaticDI::getEntityManager();
        $em->remove($this);
        $em->flush($this);
    }

    public function eq(AbstractEntity $entity): bool
    {
        if ($this === $entity) {
            return true;
        } elseif (get_class($this) !== get_class($entity) ||
            !method_exists($this, 'getId') ||
            !method_exists($entity, 'getId')
        ) {
            return false;
        }
        $id = $this->getId();
        if ($id !== null && $id === $entity->getId()) {
            return true;
        }
        return false;
    }

    /**
     * compare entities by properties values
     *
     * @param AbstractEntity $entity
     *
     * @return bool
     */
    public function isSame(AbstractEntity $entity): bool
    {
        if ($this === $entity) {
            return true;
        }
        if (get_class($this) !== get_class($entity)) {
            return false;
        }

        foreach (get_object_vars($entity) as $property => $value) {
            if ($value != $this->$property) {
                return false;
            }
        }

        return true;
    }
}
