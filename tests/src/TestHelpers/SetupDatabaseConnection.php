<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\TestHelpers;

use Doctrine\DBAL\DriverManager;

trait SetupDatabaseConnection
{
    /** @var \Doctrine\DBAL\Connection */
    private $connection;

    /**
     * @param array $options
     * @return \Doctrine\DBAL\Connection
     */
    protected function connection(array $options)
    {
        if (!$this->connection) {
            $this->connection = DriverManager::getConnection($options['dbal']);
        }

        return $this->connection;
    }
}
