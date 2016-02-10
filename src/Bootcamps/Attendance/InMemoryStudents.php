<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps\Attendance;

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
     * @param array $addresses
     */
    public function attending(DateTime $today, array $addresses)
    {
        /** @var Student $student */
        foreach ($this->students as $student) {
            if ($student->hasCheckedIn()) {

            }
        }
    }
}
