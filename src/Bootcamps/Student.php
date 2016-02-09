<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

class Student
{
    /** @var Bootcamp */
    private $bootcamp;

    /** @var string */
    private $name;

    /** @var MacAddress */
    private $macAddress;

    /**
     * @param Bootcamp $bootcamp
     * @param string $name
     * @param MacAddress $macAddress
     */
    private function __construct(Bootcamp $bootcamp, $name, MacAddress $macAddress)
    {
        $this->bootcamp = $bootcamp;
        $this->setName($name);
        $this->macAddress = $macAddress;
    }

    /**
     * @param Bootcamp $bootcamp
     * @param string $name
     * @param MacAddress $macAddress
     * @return Student
     */
    public static function attend(Bootcamp $bootcamp, $name, MacAddress $macAddress)
    {
        return new Student($bootcamp, $name, $macAddress);
    }

    /**
     * @param $name
     */
    private function setName($name)
    {
        AssertValueIs::string($name);
        AssertValueIs::notEmpty(trim($name));
        $this->name = $name;
    }

    public function name()
    {
        return $this->name;
    }

    public function checkIn(DateTime $now)
    {
        // TODO: write logic here
    }

    public function checkOut(DateTime $now)
    {
        // TODO: write logic here
    }

    public function hasCheckedIn()
    {
        return true;
    }

    public function hasCheckedOut()
    {
        return true;
    }
}
