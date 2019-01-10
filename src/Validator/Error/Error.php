<?php

namespace RvltDigital\SymfonyRevoltaBundle\Validator\Error;

class Error
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $class;

    /**
     * @param string $message
     * @param string|null $name
     * @param string|null $class
     */
    public function __construct(string $message, string $name = null, string $class = null)
    {
        $this->message = $message;
        $this->name = $name;
        $this->class = $class;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'name' => $this->name,
            'class' => $this->class,
        ];
    }
}
