<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use Codeup\DomainEvents\CanRecordEvents;
use Codeup\DomainEvents\RecordsEvents;
use DateTimeInterface;

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

    /** @var Attendance */
    private $checkOut;

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
        $this->checkIn = $attendance;
        $this->recordThat(new StudentHasCheckedIn($attendance->id()));
        return $this->checkIn;
    }

    /**
     * @param Attendance $attendance
     * @return Attendance
     */
    public function checkout(Attendance $attendance)
    {
        $this->checkOut = $attendance;
        return $this->checkOut;
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
        $student = new Student(
            StudentId::fromLiteral($storedValues['student_id']),
            Bootcamp::from($storedValues),
            $storedValues['name'],
            MacAddress::withValue($storedValues['mac_address'])
        );
        if (!is_null($storedValues['check_in_id'])) {
            $student->checkIn = Attendance::from([
                'attendance_id' => $storedValues['check_in_id'],
                'date' => $storedValues['check_in_date'],
                'type' => $storedValues['check_in_type'],
                'student_id' => $storedValues['student_id'],
            ]);
        }
        if (!is_null($storedValues['check_out_id'])) {
            $student->checkOut = Attendance::from([
                'attendance_id' => $storedValues['check_out_id'],
                'date' => $storedValues['check_out_date'],
                'type' => $storedValues['check_out_type'],
                'student_id' => $storedValues['student_id'],
            ]);
        }

        return $student;
    }

    /**
     * @param DateTimeInterface $now
     * @return Attendance
     */
    public function updateCheckout(DateTimeInterface $now)
    {
        $this->checkOut->update($now);

        return $this->checkOut;
    }

    public function hasCheckedOut($today)
    {
        return !is_null($this->checkOut) && $this->checkOut->occurredOn($today);
    }
}
