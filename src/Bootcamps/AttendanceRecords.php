<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

interface AttendanceRecords
{
    /**
     * @param Attendance $attendance
     */
    public function register(Attendance $attendance);
}
