<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\TestHelpers\SetupDatabaseConnection;
use PHPUnit_Framework_TestCase as TestCase;

class AttendanceRepositoryTest extends TestCase
{
    use SetupDatabaseConnection;

    /** @var AttendancesRepository */
    private $attendances;

    /** @before */
    function setupRepository()
    {
        $this->attendances = new AttendancesRepository(
            $connection =$this->connection(require __DIR__ . '/../../../config.php')
        );
        $connection->executeQuery('UPDATE attendances_seq SET next_val = 0');
    }

    /** @test */
    function it_should_generate_next_identity_value()
    {
        $this->assertEquals(1, $this->attendances->nextAttendanceId()->value());
        $this->assertEquals(2, $this->attendances->nextAttendanceId()->value());
        $this->assertEquals(3, $this->attendances->nextAttendanceId()->value());
    }
}
