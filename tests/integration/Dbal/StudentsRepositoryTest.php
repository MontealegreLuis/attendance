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
        $connection = $this->connection(
            require __DIR__ . '/../../../config.tests.php'
        );
        $connection->query('DELETE FROM students');

        return new StudentsRepository($connection);
    }

    public function bootcampsInstance()
    {
        $connection = $this->connection(
            require __DIR__ . '/../../../config.tests.php'
        );
        $connection->query('DELETE FROM bootcamps');

        return new BootcampsRepository($connection);
    }
}
