<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\ContractTests\StudentsTest;
use Doctrine\DBAL\DriverManager;

class StudentsRepositoryTest extends StudentsTest
{
    /** @var \Doctrine\DBAL\Connection */
    private $connection;

    /**
     * @return StudentsRepository
     * @throws \Doctrine\DBAL\DBALException
     */
    public function studentsInstance()
    {
        $connection = $this->connection();
        $connection->query('DELETE FROM students');

        return new StudentsRepository($connection);
    }

    public function bootcampsInstance()
    {
        $connection = $this->connection();
        $connection->query('DELETE FROM bootcamps');

        return new BootcampsRepository($connection);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    private function connection()
    {
        if (!$this->connection) {
            $options = require __DIR__ . '/../../../config.php';

            $this->connection = DriverManager::getConnection($options['dbal']);
        }

        return $this->connection;
    }
}
