<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\ContractTests;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Bootcamps;
use Codeup\Bootcamps\Students;
use Codeup\DataBuilders\A;
use Codeup\Fixtures\Bootcamp5Students1OnTime1Late;
use Faker\Provider\Base as Provider;
use Nelmio\Alice\PersisterInterface;
use PHPUnit_Framework_TestCase as TestCase;

abstract class AttendancesTest extends TestCase
{
    const KNOWN_ID = 1;
    const UNKNOWN_ID = 1234;

    /** @var Attendances */
    private $attendances;

    /** @var Students */
    private $students;

    /** @var Bootcamps */
    private $bootcamps;

    /**
     * @return Attendances
     */
    abstract public function attendancesInstance();

    /**
     * @return Students
     */
    abstract public function studentsInstance();

    /**
     * @return Bootcamps
     */
    abstract public function bootcampsInstance();

    /**
     * @return PersisterInterface
     */
    abstract public function persister();

    /**
     * @return Provider
     */
    abstract public function provider();

    /** @before */
    function generateFixtures()
    {
        $this->attendances = $this->attendancesInstance();
        $this->students = $this->studentsInstance();
        $this->bootcamps = $this->bootcampsInstance();

        $fixture = new Bootcamp5Students1OnTime1Late();
        $fixture->load($this->persister(), $this->provider());
    }

    /** @test */
    function it_should_generate_next_identity_value()
    {
        $this->assertEquals(3, $this->attendances->nextAttendanceId()->value());
        $this->assertEquals(4, $this->attendances->nextAttendanceId()->value());
        $this->assertEquals(5, $this->attendances->nextAttendanceId()->value());
    }

    /** @test */
    function it_finds_an_attendance_by_its_id()
    {
        $attendance = $this->attendances->with(
            AttendanceId::fromLiteral(self::KNOWN_ID)
        );

        $this->assertInstanceOf(Attendance::class, $attendance);
        $this->assertEquals(self::KNOWN_ID, $attendance->id()->value());
    }

    /** @test */
    function it_does_not_find_attendance_with_unknown_id()
    {
        $attendance = $this->attendances->with(
            AttendanceId::fromLiteral(self::UNKNOWN_ID)
        );

        $this->assertNull($attendance);
    }

    /** @test */
    function it_calculates_the_attendance_summary_for_today()
    {
        $expectedBootcampId = 1;
        $studentsInClass = 2;
        $studentsOnTime = 1;

        $summary = $this->attendances->summary(
            AttendanceId::fromLiteral(self::KNOWN_ID)
        );

        $this->assertEquals($expectedBootcampId, $summary['bootcamp_id']);
        $this->assertEquals($studentsInClass, $summary['students_count']);
        $this->assertEquals($studentsOnTime, $summary['on_time_students_count']);
    }
}
