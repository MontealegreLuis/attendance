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
    public function __construct(DateTime $date, $type, Student $student)
    {
        $this->date = $date;
        $this->type = $type;
        $this->student = $student;
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
     * @return StudentInformation
     */
    public function student()
    {
        return $this->student->information();
    }
}
