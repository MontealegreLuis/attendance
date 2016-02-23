<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

class AttendanceInformation
{
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
    public function __construct(
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
     * @return DateTime
     */
    public function onDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return StudentId
     */
    public function studentId()
    {
        return $this->studentId;
    }

    /**
     * @return AttendanceId
     */
    public function id()
    {
        return $this->attendanceId;
    }
}
