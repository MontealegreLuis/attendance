<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps\Attendance;

use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\StudentId;
use Codeup\Bootcamps\Students;
use DateTimeInterface;
use SplObjectStorage;

class InMemoryStudents implements Students
{
    private static $nextStudentId = 0;

    /** @var SplObjectStorage */
    private $students;

    public function __construct()
    {
        $this->students = new SplObjectStorage();
    }

    /**
     * @return StudentId
     */
    public function nextStudentId()
    {
        self::$nextStudentId++;

        return StudentId::fromLiteral(self::$nextStudentId);
    }

    /**
     * @param Student $student
     */
    public function add(Student $student)
    {
        $this->students->attach($student);
    }

    /**
     * @param Student $student
     */
    public function update(Student $student)
    {
        $this->students->attach($student);
    }

    /**
     * @param StudentId $studentId
     * @return Student
     */
    public function with(StudentId $studentId)
    {
        /** @var Student $student */
        foreach ($this->students as $student) {
            if ($student->id()->equals($studentId)) {
                return $student;
            }
        }
    }

    /**
     * @param DateTimeInterface $today
     * @param MacAddress[] $addresses
     * @return Student[]
     */
    public function attending(DateTimeInterface $today, array $addresses)
    {
        $students = [];

        /** @var Student $student */
        foreach ($this->students as $student) {
            if ($this->isStudentPresent($student, $today, $addresses)) {
                $students[] = $student;
            }
        }

        return $students;
    }

    /**
     * Filter students first by checking if they're enrolled in a bootcamp now,
     * then check if one of the registered MAC addresses belong to one of them.
     *
     * @param Student $student
     * @param DateTimeInterface $today
     * @param MacAddress[] $addresses
     * @return bool
     */
    private function isStudentPresent(
        Student $student,
        DateTimeInterface $today,
        array $addresses
    ) {
        /** @var MacAddress $address */
        foreach ($addresses as $address) {
            if ($student->isInClass($today) && $student->has($address)) {
                return true;
            }
        }
        return false;
    }
}
