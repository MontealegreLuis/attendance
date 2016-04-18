<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\ContractTests\StudentsTest;
use Codeup\TestHelpers\SetupDatabaseConnection;

class StudentsRepositoryTest extends StudentsTest
{
    use SetupDatabaseConnection;

    /**
     * @return StudentsRepository
     * @throws \Doctrine\DBAL\DBALException
     */
    public function studentsInstance()
    {
        $connection = $this->connection();
        $connection->query('DELETE FROM students');
        $connection->executeQuery('UPDATE students_seq SET next_val = 0');

        return new StudentsRepository($connection);
    }

    /**
     * @return BootcampsRepository
     * @throws \Doctrine\DBAL\DBALException
     */
    public function bootcampsInstance()
    {
        $connection = $this->connection();
        $connection->query('DELETE FROM bootcamps');
        $connection->executeQuery('UPDATE bootcamps_seq SET next_val = 0');

        return new BootcampsRepository($connection);
    }
}
