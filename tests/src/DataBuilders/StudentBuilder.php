<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\StudentId;
use DateTimeInterface;

class StudentBuilder
{
    use ProvidesFakeDataGenerator;

    /** @var int */
    private static $nextId = 0;

    /** @var StudentId */
    private $studentId;

    /** @var Bootcamp */
    private $bootcamp;

    /** @var string */
    private $name;

    /** @var MacAddress */
    private $macAddress;

    /** @var Attendance */
    private $checkIn;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * @return Student
     */
    public function build()
    {
        $student = Student::attend(
            $this->studentId,
            $this->bootcamp,
            $this->name,
            $this->macAddress
        );
        if ($this->checkIn) {
            $student->register($this->checkIn);
        }
        $this->reset();

        return $student;
    }

    /**
     * @param Bootcamp $bootcamp
     * @return BootcampBuilder
     */
    public function enrolledOn(Bootcamp $bootcamp)
    {
        $this->bootcamp = $bootcamp;

        return $this;
    }

    /**
     * @param MacAddress $address
     * @return StudentBuilder
     */
    public function withMacAddress(MacAddress $address)
    {
        $this->macAddress = $address;

        return $this;
    }

    /**
     * @param DateTimeInterface $time
     * @return StudentBuilder
     */
    public function whoCheckedInAt(DateTimeInterface $time)
    {
        $this->checkIn = Attendance::checkIn(
            AttendanceId::fromLiteral(static::$nextId),
            $time,
            $this->studentId
        );

        return $this;
    }

    private function reset()
    {
        $this->name = $this->generator()->name;
        $this->macAddress = MacAddress::withValue($this->generator()->macAddress);
        static::$nextId++;
        $this->studentId = StudentId::fromLiteral(static::$nextId);
        $this->checkIn = null;
    }
}
