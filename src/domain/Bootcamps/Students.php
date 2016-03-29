<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

interface Students
{
    /**
     * Retrieve all the students who are currently enrolled in a bootcamp that
     * is not yet finished
     *
     * It does not matter if they have checked in/out today yet. The
     * appropriate action will be taken if needed in a subsequent step.
     *
     * @param DateTime $today
     * @param array $addresses
     * @return Student[]
     */
    public function attending(DateTime $today, array $addresses);

    /**
     * @param Student $student
     */
    public function add(Student $student);

    /**
     * @param Student $student
     */
    public function update(Student $student);

    /**
     * @param StudentId $studentId
     * @return Student
     */
    public function with(StudentId $studentId);
}
