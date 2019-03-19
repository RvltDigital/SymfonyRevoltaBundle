<?php

namespace RvltDigital\SymfonyRevoltaBundle\Exception;

use Throwable;

class RedisKeyNotFoundException extends \RuntimeException
{
    public function __construct(string $key, Throwable $previous = null)
    {
        $message = sprintf("The Redis key '%s' does not exist", $key);
        parent::__construct($message, 0, $previous);
    }
}
