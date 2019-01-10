<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Error;

class ErrorStack implements \Countable
{

    /**
     * @var Error[]
     */
    protected $errors = [];

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param Error $error
     */
    public function addError(Error $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param ErrorStack $stack
     */
    public function merge(ErrorStack $stack): void
    {
        $this->errors = array_merge(
            $this->errors,
            $stack->getErrors()
        );
    }

    /**
     * @param string $name
     * @return ErrorStack
     */
    public function filter(string $name): self
    {
        $stack = new self();
        foreach ($this->errors as $error) {
            if ($error->getName() === $name) {
                $stack->addError($error);
            }
        }
        return $stack;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->errors);
    }

    public function empty()
    {
        return empty($this->errors);
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return array_map(function ($error) {
            /** @var Error $error */
            return $error->getMessage();
        }, $this->errors);
    }
}
