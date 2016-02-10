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
}
