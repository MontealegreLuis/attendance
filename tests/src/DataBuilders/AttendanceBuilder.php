<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\StudentId;

class AttendanceBuilder
{
    use ProvidesFakeDataGenerator;

    /** @var int */
    private $id;

    /** @var \DateTime */
    private $date;

    /** @var StudentId */
    private $studentId;

    public function __construct()
    {
        $this->reset();
    }

    /**
     * @return Attendance
     */
    public function build()
    {
        $attendance = Attendance::checkIn(
            AttendanceId::fromLiteral($this->id),
            $this->date,
            $this->studentId
        );

        $this->reset();

        return $attendance;
    }

    /**
     * @param int $id
     * @return AttendanceBuilder
     */
    public function withId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param StudentId $studentId
     * @return AttendanceBuilder
     */
    public function withStudentId(StudentId $studentId)
    {
        $this->studentId = $studentId;

        return $this;
    }

    private function reset()
    {
        $this->date = $this->generator()->dateTime;
    }
}
