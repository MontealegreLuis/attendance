<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Codeup\Bootcamp;
use Codeup\MacAddress;
use Codeup\Student;
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

    public function build()
    {
        return Student::attend($this->bootcamp, $this->name, $this->macAddress);
    }

    private function reset()
    {
        $this->bootcamp = Bootcamp::start(
            $this->factory->dateTime,
            $this->factory->word
        );
        $this->name = $this->factory->name;
        $this->macAddress = MacAddress::withValue($this->factory->macAddress);
    }
}
