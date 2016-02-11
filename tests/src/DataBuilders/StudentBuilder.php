<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\Schedule;
use Codeup\Bootcamps\Duration;
use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\StudentId;
use Faker\Factory;
use DateTime;

class StudentBuilder
{
    private $factory;

    /** @var int */
    private $nextId = 0;

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
        $this->reset();
    }

    /**
     * @return Student
     */
    public function build()
    {
        $student = Student::attend(
            StudentId::fromLiteral($this->nextId),
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
        $this->bootcamp = Bootcamp::start(
            Duration::between(
                $this->factory->dateTimeBetween('-7 day', '-3 day'),
                $this->factory->dateTimeBetween('-2 day', '-1 day')
            ),
            $this->factory->word,
            Schedule::withClassTimeBetween(
                $this->factory->dateTimeBetween('-7 day'),
                $this->factory->dateTimeBetween('1 day', '7 day')
            )
        );

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
        $this->bootcamp = Bootcamp::start(
            Duration::between(
                $this->factory->dateTimeBetween('-7 day', '-1 day'),
                $this->factory->dateTimeBetween('1 day', '7 day')
            ),
            $this->factory->word,
            Schedule::withClassTimeBetween(
                $this->factory->dateTimeBetween('-7 day'),
                $this->factory->dateTimeBetween('1 day', '7 day')
            )
        );
        $this->name = $this->factory->name;
        $this->macAddress = MacAddress::withValue($this->factory->macAddress);
        $this->nextId++;
        $this->checkIn = null;
    }
}
