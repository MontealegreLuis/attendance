<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;

abstract class DatabaseCommand extends Command
{
    /**
     * @param array $parameters
     * @return array
     */
    protected function withoutDatabaseName(array $parameters)
    {
        $filtered = $parameters;
        unset($filtered['dbname'], $filtered['path']);

        return $filtered;
    }

    /**
     * @param array $parameters
     * @param Connection $connection
     * @return bool
     */
    protected function databaseExists(array $parameters, Connection $connection)
    {
        if ($this->hasPath($parameters)) {
            return file_exists($this->databaseName($parameters));
        }

        return in_array(
            $this->databaseName($parameters),
            $connection->getSchemaManager()->listDatabases()
        );
    }

    /**
     * @param array $parameters
     * @return string
     */
    protected function databaseName(array $parameters)
    {
        return $this->hasPath($parameters) ? $parameters['path'] : $parameters['dbname'];
    }

    /**
     * @param array $parameters
     * @return bool
     */
    protected function hasPath(array $parameters)
    {
        return isset($parameters['path']);
    }
}
