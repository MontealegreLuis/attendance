<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\Bootcamps\AttendanceChecker;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\Students;
use DateTime;

/**
 * Poll the router's DHCP status page to see who is connected, if the MAC
 * addresses belong to a student check if it has already checked in for the day.
 * If not, then check the student in and persist that information.
 */
class DoRollCall
{
    /** @var AttendanceChecker */
    private $checker;

    /** @var Students */
    private $students;

    /**
     * @param AttendanceChecker $checker
     * @param Students $students
     */
    public function __construct(AttendanceChecker $checker, Students $students)
    {
        $this->checker = $checker;
        $this->students = $students;
    }

    /**
     * @param DateTime $today
     * @return \Codeup\Bootcamps\Student[]
     */
    public function rollCall(DateTime $today)
    {
        $addresses = $this->checker->whoIsConnected();
        $students = $this->students->attending($today, $addresses);
        $studentsInClass = [];

        /** @var Student $student */
        foreach ($students as $student) {
            if (!$student->hasCheckedIn($today)) {
                $student->checkIn($today);
                $studentsInClass[] = $student;
                $this->students->update($student);
            }
        }

        return $studentsInClass;
    }
}
