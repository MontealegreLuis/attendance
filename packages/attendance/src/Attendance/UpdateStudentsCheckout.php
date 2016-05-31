<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceChecker;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\Students;
use DateTimeInterface;

class UpdateStudentsCheckout
{
    /** @var AttendanceChecker */
    private $checker;

    /** @var Students */
    private $students;

    private $attendances;

    /**
     * @param AttendanceChecker $checker
     * @param Students $students
     * @param Attendances $attendances
     */
    public function __construct(
        AttendanceChecker $checker,
        Students $students,
        Attendances $attendances
    ) {
        $this->checker = $checker;
        $this->students = $students;
        $this->attendances = $attendances;
    }

    /**
     * @param DateTimeInterface $today
     * @return array
     */
    public function updateStudentsCheckout(DateTimeInterface $today)
    {
        $addresses = $this->checker->whoIsConnected();
        $students = $this->students->attending($today, $addresses);
        $studentsInClass = [];

        /** @var Student $student */
        foreach ($students as $student) {
            $studentsInClass = $this->registerStudentCheckOut(
                $today,
                $student,
                $studentsInClass
            );
        }

        return $studentsInClass;
    }

    public function registerStudentCheckOut(
        DateTimeInterface $today,
        Student $student,
        array $students
    ) {
        if ($student->hasCheckedIn($today)) {
            $this->attendances->add($student->update(Attendance::checkOut(
                $this->attendances->nextAttendanceId(),
                $today,
                $student->id()
            )));
            $students[] = $student;
        }
        return $students;
    }
}
