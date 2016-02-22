<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps\Attendance;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\Attendances;
use SplObjectStorage;

class InMemoryAttendances implements Attendances
{
    /** @var SplObjectStorage */
    private $attendances;

    public function __construct()
    {
        $this->attendances = new SplObjectStorage();
    }

    /**
     * @param Attendance $attendance
     */
    public function add(Attendance $attendance)
    {
        $this->attendances->attach($attendance);
    }
}
