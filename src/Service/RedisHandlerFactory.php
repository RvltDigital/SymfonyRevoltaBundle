<?php

namespace RvltDigital\SymfonyRevoltaBundle\Service;

use RvltDigital\StaticDiBundle\StaticDI;

final class RedisHandlerFactory
{

    /**
     * @var \Redis|null
     */
    private $handler = null;

    public function getInstance(): \Redis
    {
        if (!is_null($this->handler)) {
            return $this->handler;
        }

        $config = StaticDI::getParameter('redis');
        if (!is_array($config)) {
            throw new \LogicException("The parameter 'redis' must be configured and must be an array");
        }
        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? 6379;
        $database = $config['database'] ?? 0;

        $handler = new \Redis();
        if (!$handler->connect($host, $port)) {
            throw new \LogicException("Could not connect to host '{$host}' on port '{$port}'");
        }
        if (!$handler->select($database)) {
            throw new \LogicException("Could not select database '{$database}'");
        }

        $this->handler = $handler;
        return $handler;
    }
}
