<?php

namespace RvltDigital\SymfonyRevoltaBundle\Doctrine;

use Doctrine\DBAL\Driver\PDOPgSql\Driver;
use RvltDigital\StaticDiBundle\StaticDI;

class PostgresSchemaDriver extends Driver
{
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        $connection = parent::connect($params, $username, $password, $driverOptions);
        $schemas = implode(',', $this->getSchemas());
        $connection->exec("SET SEARCH_PATH TO {$schemas}");

        return $connection;
    }

    /**
     * @return string[]
     */
    private function getSchemas(): array
    {
        $schemas = StaticDI::getParameter('rvlt_digital.internal.revolta.postgres_schemas');
        if (!is_array($schemas)) {
            $schemas = [];
        }

        if (in_array('public', $schemas)) { // remove public to ensure it's last
            unset($schemas[array_search('public', $schemas)]);
        }
        $schemas[] = 'public';

        return $schemas;
    }
}
