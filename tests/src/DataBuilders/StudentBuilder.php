<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\BootcampSchedule;
use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\Student;
use Faker\Factory;

class StudentBuilder
{
    private $factory;

    /** @var Bootcamp */
    private $bootcamp;

    /** @var string */
    private $name;

    /** @var MacAddress */
    private $macAddress;

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
        return Student::attend($this->bootcamp, $this->name, $this->macAddress);
    }

    private function reset()
    {
        $this->bootcamp = Bootcamp::start(
            $this->factory->dateTime,
            $this->factory->word,
            BootcampSchedule::withClassTimeBetween(
                $this->factory->dateTimeBetween('-7 day'),
                $this->factory->dateTimeBetween('1 day', '7 day')
            )
        );
        $this->name = $this->factory->name;
        $this->macAddress = MacAddress::withValue($this->factory->macAddress);
    }
}
