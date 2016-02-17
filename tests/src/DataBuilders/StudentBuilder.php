<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Schedule;
use Codeup\Bootcamps\Duration;
use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\StudentId;
use Faker\Factory;
use DateTime;

class StudentBuilder
{
    /** @var \Faker\Generator */
    private $factory;

    /** @var  BootcampBuilder */
    private $bootcampBuilder;

    /** @var int */
    private static $nextId = 0;

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
        $this->factory = Factory::create();
        $this->bootcampBuilder = new BootcampBuilder();
        $this->reset();
    }

    /**
     * @return Student
     */
    public function build()
    {
        $student = Student::attend(
            StudentId::fromLiteral(static::$nextId),
            $this->bootcamp,
            $this->name,
            $this->macAddress
        );
        if ($this->checkIn) {
            $student->checkIn($this->checkIn);
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
     * @return StudentBuilder
     */
    public function enrrolledInABootcampAlreadyFinished()
    {
        $this->bootcamp = $this->bootcampBuilder->alreadyFinished()->build();

        return $this;
    }

    /**
     * @param DateTime $time
     * @return StudentBuilder
     */
    public function whoCheckedInAt(DateTime $time)
    {
        $this->checkIn = $time;

        return $this;
    }

    private function reset()
    {
        $this->bootcamp = $this->bootcampBuilder->build();
        $this->name = $this->factory->name;
        $this->macAddress = MacAddress::withValue($this->factory->macAddress);
        static::$nextId++;
        $this->checkIn = null;
    }
}
