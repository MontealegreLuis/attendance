<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\Bootcamps\AttendanceChecker;
use DateTime;

class PerformRollCall
{
    private $checker;

    public function __construct(AttendanceChecker $checker)
    {
        $this->checker = $checker;
    }

    public function rollCall()
    {
        $addresses = $this->checker->whoIsConnected();
        $today = new DateTime();
        // Check if the bootcamp has not ended yet
        // Check if the student have already checked in
        // If not save the record.
    }
}
