<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

interface Attendances
{
    /**
     * @return AttendanceId
     */
    public function nextAttendanceId();

    /**
     * @param Attendance $attendance
     */
    public function add(Attendance $attendance);
}
