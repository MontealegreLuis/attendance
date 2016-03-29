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
    /** @var int */
    private $knownId;

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

    /** @before */
    function generateFixtures()
    {
        $this->attendances = $this->attendancesInstance();
        $this->students = $this->studentsInstance();
        $this->bootcamps = $this->bootcampsInstance();

        $this->knownId = 4;
        $this->bootcamps->add(
            $bootcamp = A::bootcamp()
                ->notYetFinished(new DateTime('now'))
                ->build()
        );
        $this->students->add(
            $student = A::student()->enrolledOn($bootcamp)->build()
        );
        $this->attendances->add(
            $attendance = A::attendance()
                ->withId($this->knownId)
                ->withStudentId($student->id())
                ->build()
        );
    }

    /** @test */
    function it_should_generate_next_identity_value()
    {
        $this->assertEquals(1, $this->attendances->nextAttendanceId()->value());
        $this->assertEquals(2, $this->attendances->nextAttendanceId()->value());
        $this->assertEquals(3, $this->attendances->nextAttendanceId()->value());
    }

    /** @test */
    function it_finds_an_attendance_by_its_id()
    {
        $attendance = $this->attendances->with(
            AttendanceId::fromLiteral($this->knownId)
        );

        $this->assertEquals($this->knownId, $attendance->id()->value());
    }

    /** @test */
    function it_does_not_find_attendance_with_unknown_id()
    {
        $attendance = $this->attendances->with(
            AttendanceId::fromLiteral($unknownId = 1234)
        );

        $this->assertNull($attendance);
    }
}
