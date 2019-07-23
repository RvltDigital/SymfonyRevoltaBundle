<?php

namespace RvltDigital\SymfonyRevoltaBundle\Doctrine;

use Doctrine\DBAL\Driver\PDOPgSql\Driver;
use RvltDigital\StaticDiBundle\StaticDI;

class PostgresSchemaDriver extends Driver
{
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        $connection = parent::connect($params, $username, $password, $driverOptions);
        $connection->exec("SET SEARCH_PATH TO {$this->getSchema()}");

        return $connection;
    }

    private function getSchema(): string
    {
        $schema = StaticDI::getParameter('rvlt_digital.internal.revolta.postgres_schema');
        if (!$schema) {
            $schema = 'public';
        }

        return $schema;
    }
}
