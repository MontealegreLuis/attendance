<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

class StudentInformation
{
    /** @var StudentId */
    private $studentId;

    /** @var BootcampInformation */
    private $bootcamp;

    /** @var string */
    private $name;

    /** @var MacAddress */
    private $macAddress;

    /**
     * @param StudentId $studentId
     * @param BootcampInformation $bootcamp
     * @param string $name
     * @param MacAddress $macAddress
     */
    public function __construct(
        StudentId $studentId,
        BootcampInformation $bootcamp,
        $name,
        MacAddress $macAddress
    ) {
        $this->studentId = $studentId;
        $this->bootcamp = $bootcamp;
        $this->name = $name;
        $this->macAddress = $macAddress;
    }

    /**
     * @return StudentId
     */
    public function id()
    {
        return $this->studentId;
    }

    /**
     * @return BootcampInformation
     */
    public function bootcamp()
    {
        return $this->bootcamp;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return MacAddress
     */
    public function macAddress()
    {
        return $this->macAddress;
    }
}
