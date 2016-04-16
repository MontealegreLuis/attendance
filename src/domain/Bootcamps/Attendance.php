<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;
use DateTimeInterface;

/**
 * There are two types of attendances to be tracked: when the students arrive
 * to the class and when they leave.
 */
class Attendance
{
    const CHECK_IN = 0;
    const CHECK_OUT = 1;

    /** @var AttendanceId */
    private $attendanceId;

    /** @var DateTimeInterface */
    private $date;

    /** @var int */
    private $type;

    /** @var StudentId */
    private $studentId;

    /**
     * @param AttendanceId $attendanceId
     * @param DateTimeInterface $date
     * @param int $type
     * @param StudentId $studentId
     */
    private function __construct(
        AttendanceId $attendanceId,
        DateTimeInterface $date,
        $type,
        StudentId $studentId
    ) {
        $this->date = $date;
        $this->type = $type;
        $this->studentId = $studentId;
        $this->attendanceId = $attendanceId;
    }

    /**
     * @param array $storedValues
     * @return Attendance
     */
    public static function from(array $storedValues)
    {
        return new Attendance(
            AttendanceId::fromLiteral($storedValues['attendance_id']),
            DateTime::createFromFormat('Y-m-d H:i:s', $storedValues['date']),
            (int) $storedValues['type'],
            StudentId::fromLiteral($storedValues['student_id'])
        );
    }

    /**
     * @param AttendanceId $attendanceId
     * @param DateTimeInterface $aDate
     * @param StudentId $studentId
     * @return Attendance
     */
    public static function checkOut(
        AttendanceId $attendanceId,
        DateTimeInterface $aDate,
        StudentId $studentId
    ) {
        return new Attendance(
            $attendanceId,
            $aDate,
            self::CHECK_OUT,
            $studentId
        );
    }

    /**
     * @param AttendanceId $attendanceId
     * @param DateTimeInterface $aDate
     * @param StudentId $studentId
     * @return Attendance
     */
    public static function checkIn(
        AttendanceId $attendanceId,
        DateTimeInterface $aDate,
        StudentId $studentId
    ) {
        return new Attendance(
            $attendanceId,
            $aDate,
            self::CHECK_IN,
            $studentId
        );
    }

    /**
     * @return AttendanceId
     */
    public function id()
    {
        return $this->attendanceId;
    }

    /**
     * @return bool
     */
    public function isCheckIn()
    {
        return $this->type === static::CHECK_IN;
    }

    /**
     * @param DateTimeInterface $aDate
     * @return bool
     */
    public function occurredOn(DateTimeInterface $aDate)
    {
        return $this->date->format('Y-m-d') === $aDate->format('Y-m-d');
    }

    /**
     * @return AttendanceInformation
     */
    public function information()
    {
        return new AttendanceInformation(
            $this->attendanceId,
            $this->date,
            $this->type,
            $this->studentId
        );
    }
}
