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

    /** @var Attendance */
    private $checkIn;

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

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param DateTime $now
     */
    public function checkIn(DateTime $now)
    {
        $this->checkIn = Attendance::checkIn($now, $this);
    }

    /**
     * @param DateTime $today
     * @return bool
     */
    public function hasCheckedIn(DateTime $today)
    {
        return !is_null($this->checkIn) && $this->checkIn->occurredOn($today);
    }

    /**
     * @param DateTime $today
     * @return bool
     */
    public function isInClass(DateTime $today)
    {
        return $this->bootcamp->isInProgress($today);
    }

    /**
     * @param MacAddress $macAddress
     */
    public function has(MacAddress $macAddress)
    {
        $this->macAddress->equals($macAddress);
    }

    public function checkOut(DateTime $now)
    {
        // TODO: write logic here
    }

    public function hasCheckedOut()
    {
        return true;
    }
}
