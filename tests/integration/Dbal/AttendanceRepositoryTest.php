<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\ContractTests\AttendancesTest;
use Codeup\TestHelpers\SetupDatabaseConnection;

class AttendanceRepositoryTest extends AttendancesTest
{
    use SetupDatabaseConnection;

    /**
     * @return AttendancesRepository
     * @throws \Doctrine\DBAL\DBALException
     */
    public function attendancesInstance()
    {
        $connection = $this->connection();
        $connection->query('DELETE FROM attendances');
        $connection->executeQuery('UPDATE attendances_seq SET next_val = 0');

        return new AttendancesRepository($connection);
    }

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
