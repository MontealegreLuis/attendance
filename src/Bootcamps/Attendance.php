<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

class Attendance
{
    const CHECK_IN = 0;
    const CHECK_OUT = 1;

    /** @var AttendanceId */
    private $attendanceId;

    /** @var DateTime */
    private $date;

    /** @var int */
    private $type;

    /** @var StudentId */
    private $studentId;

    /**
     * @param AttendanceId $attendanceId
     * @param DateTime $date
     * @param int $type
     * @param StudentId $studentId
     */
    private function __construct(
        AttendanceId $attendanceId,
        DateTime $date,
        $type,
        StudentId $studentId
    ) {
        $this->date = $date;
        $this->type = $type;
        $this->studentId = $studentId;
        $this->attendanceId = $attendanceId;
    }

    /**
     * @param AttendanceId $attendanceId
     * @param DateTime $aDate
     * @param StudentId $studentId
     * @return Attendance
     */
    public static function checkOut(
        AttendanceId $attendanceId,
        DateTime $aDate,
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
     * @param DateTime $aDate
     * @param StudentId $studentId
     * @return Attendance
     */
    public static function checkIn(
        AttendanceId $attendanceId,
        DateTime $aDate,
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
     * @param DateTime $aDate
     * @return bool
     */
    public function occurredOn(DateTime $aDate)
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
