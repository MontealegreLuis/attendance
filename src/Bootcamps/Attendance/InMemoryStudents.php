<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps\Attendance;

use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\Students;
use DateTime;
use SplObjectStorage;

class InMemoryStudents implements Students
{
    /** @var SplObjectStorage */
    private $students;

    public function __construct()
    {
        $this->students = new SplObjectStorage();
    }

    /**
     * @param Student $student
     */
    public function update(Student $student)
    {
        $this->students->attach($student);
    }

    /**
     * @param DateTime $today
     * @param MacAddress[] $addresses
     * @return Student[]
     */
    public function attending(DateTime $today, array $addresses)
    {
        $students = [];

        /** @var Student $student */
        foreach ($this->students as $student) {
            if ($this->isStudentPresent($student)) {
                $students[] = $student;
            }
        }

        return $students;
    }

    /**
     * @param Student $student
     * @param DateTime $today
     * @param MacAddress[] $addresses
     * @return bool
     */
    private function isStudentPresent(
        Student $student,
        DateTime $today,
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
