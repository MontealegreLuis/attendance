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

    /** @var DateTime */
    private $date;

    /** @var int */
    private $type;

    /** @var Student */
    private $student;

    /**
     * @param DateTime $date
     * @param int $type
     * @param Student $student
     */
    private function __construct(DateTime $date, $type, Student $student)
    {
        $this->date = $date;
        $this->type = $type;
        $this->student = $student;
    }

    /**
     * @param DateTime $aDate
     * @param Student $student
     * @return Attendance
     */
    public static function checkOut(DateTime $aDate, Student $student)
    {
        return new Attendance($aDate, self::CHECK_OUT, $student);
    }

    /**
     * @param DateTime $aDate
     * @param Student $student
     * @return Attendance
     */
    public static function checkIn(DateTime $aDate, Student $student)
    {
        return new Attendance($aDate, self::CHECK_IN, $student);
    }

    /**
     * @return DateTime
     */
    public function onDate()
    {
        return $this->date;
    }
}
