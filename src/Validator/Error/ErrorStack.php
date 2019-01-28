<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Error;

class ErrorStack implements \Countable, \Iterator
{

    /**
     * For the Iterator interface
     * @var int
     */
    private $i = 0;

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

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return Error Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->errors[$this->i];
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->i;
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return int scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->i;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return array_key_exists($this->i, $this->errors);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->i = 0;
    }
}
