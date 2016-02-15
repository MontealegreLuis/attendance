<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;
use Codeup\DomainEvents\CanRecordEvents;
use Codeup\DomainEvents\RecordsEvents;

class Student implements CanRecordEvents
{
    use RecordsEvents;

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

    /**
     * @param StudentId $studentId
     * @param Bootcamp $bootcamp
     * @param string $name
     * @param MacAddress $macAddress
     */
    private function __construct(
        StudentId $studentId,
        Bootcamp $bootcamp,
        $name,
        MacAddress $macAddress
    ) {
        $this->bootcamp = $bootcamp;
        $this->setName($name);
        $this->macAddress = $macAddress;
        $this->studentId = $studentId;
    }

    /**
     * @param StudentId $studentId
     * @param Bootcamp $bootcamp
     * @param string $name
     * @param MacAddress $macAddress
     * @return Student
     */
    public static function attend(
        StudentId $studentId,
        Bootcamp $bootcamp,
        $name,
        MacAddress $macAddress
    ) {
        return new Student($studentId, $bootcamp, $name, $macAddress);
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
     * @return StudentId
     */
    public function id()
    {
        return $this->studentId;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function address()
    {
        return $this->macAddress->value();
    }

    /**
     * @param DateTime $now
     */
    public function checkIn(DateTime $now)
    {
        $this->checkIn = Attendance::checkIn($now, $this);
        $this->recordThat(new StudentHasCheckedIn($this->studentId, $now));
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
     * @return bool
     */
    public function has(MacAddress $macAddress)
    {
        return $this->macAddress->equals($macAddress);
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
