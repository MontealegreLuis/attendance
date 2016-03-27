<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;
use DateTimeInterface;
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
     * @return StudentInformation
     */
    public function information()
    {
        return new StudentInformation(
            $this->studentId,
            $this->bootcamp->information(),
            $this->name,
            $this->macAddress
        );
    }

    /**
     * @param Attendance $attendance
     * @return Attendance
     */
    public function register(Attendance $attendance)
    {
        if ($attendance->isCheckIn()) {
            $this->checkIn = $attendance;
            $this->recordThat(new StudentHasCheckedIn($attendance->id()));
            return $this->checkIn;
        }
    }

    /**
     * @param DateTimeInterface $today
     * @return bool
     */
    public function hasCheckedIn(DateTimeInterface $today)
    {
        return !is_null($this->checkIn) && $this->checkIn->occurredOn($today);
    }

    /**
     * @param DateTimeInterface $today
     * @return bool
     */
    public function isInClass(DateTimeInterface $today)
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

    /**
     * @param array $storedValues
     * @return Student
     */
    public static function from(array $storedValues)
    {
        return new Student(
            StudentId::fromLiteral($storedValues['student_id']),
            Bootcamp::start(
                BootcampId::fromLiteral($storedValues['bootcamp_id']),
                Duration::between(
                    DateTime::createFromFormat('Y-m-d', $storedValues['start_date']),
                    DateTime::createFromFormat('Y-m-d', $storedValues['stop_date'])
                ),
                $storedValues['cohort_name'],
                Schedule::withClassTimeBetween(
                    DateTime::createFromFormat('Y-m-d H:i:s', $storedValues['start_time']),
                    DateTime::createFromFormat('Y-m-d H:i:s', $storedValues['stop_time'])
                )
            ),
            $storedValues['name'],
            MacAddress::withValue($storedValues['mac_address'])
        );
    }

    public function checkOut(DateTimeInterface $now)
    {
        // TODO: write logic here
    }

    public function hasCheckedOut()
    {
        return true;
    }
}