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
use Codeup\DomainEvents\PublishesEvents;
use DateTime;

/**
 * Poll the router's DHCP status page to see who is connected, if the MAC
 * addresses belong to a student check if it has already checked in for the day.
 * If not, then check the student in and persist that information.
 */
class DoRollCall
{
    use PublishesEvents;

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
            $this->registerStudentCheckIn($today, $student, $studentsInClass);
        }

        return $studentsInClass;
    }

    /**
     * @param DateTime $today
     * @param Student $student
     * @param array $students
     */
    private function registerStudentCheckIn(
        DateTime $today,
        Student $student,
        array $students
    ) {
        if (!$student->hasCheckedIn($today)) {
            $student->checkIn($today);
            $this->students->update($student);
            $students[] = $student;
            $this->publisher()->publish($student->events());
        }
    }
}
