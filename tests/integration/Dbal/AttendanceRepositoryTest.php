<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\Bootcamps\Bootcamps;
use Codeup\Bootcamps\Students;
use Codeup\ContractTests\AttendancesTest;
use Codeup\TestHelpers\SetupDatabaseConnection;

class AttendanceRepositoryTest extends AttendancesTest
{
    use SetupDatabaseConnection;

    /** @var AttendancesRepository */
    private $attendances;

    /** @var StudentsRepository */
    private $students;

    /** @var BootcampsRepository */
    private $bootcamps;

    /**
     * @return AttendancesRepository
     * @throws \Doctrine\DBAL\DBALException
     */
    public function attendancesInstance()
    {
        if ($this->attendances) {
            return $this->attendances;
        }

        $this->attendances = new AttendancesRepository(
            $connection = $this->connection(
                require __DIR__ . '/../../../config.tests.php'
            )
        );

        $connection->query('DELETE FROM attendances');
        $connection->executeQuery('UPDATE attendances_seq SET next_val = 0');

        return $this->attendances;
    }

    public function studentsInstance()
    {
        if ($this->students) {
            return $this->students;
        }

        $this->students = new StudentsRepository(
            $connection = $this->connection(
                require __DIR__ . '/../../../config.tests.php'
            )
        );

        $connection->query('DELETE FROM students');
        //$connection->executeQuery('UPDATE students_seq SET next_val = 0');

        return $this->students;
    }

    public function bootcampsInstance()
    {
        if ($this->bootcamps) {
            return $this->bootcamps;
        }

        $this->bootcamps = new BootcampsRepository(
            $connection = $this->connection(
                require __DIR__ . '/../../../config.tests.php'
            )
        );

        $connection->query('DELETE FROM bootcamps');
        //$connection->executeQuery('UPDATE bootcamps_seq SET next_val = 0');

        return $this->bootcamps;
    }
}
