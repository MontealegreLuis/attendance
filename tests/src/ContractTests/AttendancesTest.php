<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\ContractTests;

use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\Bootcamps;
use Codeup\Bootcamps\Students;
use Codeup\DataBuilders\A;
use DateTime;
use PHPUnit_Framework_TestCase as TestCase;

abstract class AttendancesTest extends TestCase
{
    protected $knownId;

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

    /** @before */
    function generateFixtures()
    {
        $attendances = $this->attendancesInstance();
        $students = $this->studentsInstance();
        $bootcamps = $this->bootcampsInstance();

        $this->knownId = 4;
        $bootcamps->add(
            $bootcamp = A::bootcamp()
                ->notYetFinished(new DateTime('now'))
                ->build()
        );
        $students->add(
            $student = A::student()->enrolledOn($bootcamp)->build()
        );
        $attendances->add(
            $attendance = A::attendance()
                ->withId($this->knownId)
                ->withStudentId($student->id())
                ->build()
        );
    }

    /** @test */
    function it_should_generate_next_identity_value()
    {
        $attendances = $this->attendancesInstance();

        $this->assertEquals(1, $attendances->nextAttendanceId()->value());
        $this->assertEquals(2, $attendances->nextAttendanceId()->value());
        $this->assertEquals(3, $attendances->nextAttendanceId()->value());
    }

    /** @test */
    function it_finds_an_attendance_by_its_id()
    {
        $attendances = $this->attendancesInstance();
        $attendance = $attendances->with(
            AttendanceId::fromLiteral($this->knownId)
        );

        $this->assertEquals($this->knownId, $attendance->id()->value());
    }
}
