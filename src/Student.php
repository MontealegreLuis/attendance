<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup;

use Assert\Assertion;

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
     * @return Student
     */
    public static function attend(Bootcamp $bootcamp)
    {
        return new Student($bootcamp);
    }

    /**
     * @param $name
     */
    private function setName($name)
    {
        Assertion::string($name);
        Assertion::notEmpty(trim($name));
        $this->name = $name;
    }
}
