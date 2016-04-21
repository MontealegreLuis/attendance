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
     * @return \Doctrine\DBAL\Connection
     */
    protected function connection()
    {
        if (!$this->connection) {
            $options = require __DIR__ . '/../../../config.tests.php';
            $this->connection = DriverManager::getConnection($options['dbal']);
        }

        return $this->connection;
    }
}
