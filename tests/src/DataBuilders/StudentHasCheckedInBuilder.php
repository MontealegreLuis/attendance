<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\StudentHasCheckedIn;

class StudentHasCheckedInBuilder
{
    /** @var int */
    private static $nextId = 0;

    /**
     * StudentHasCheckedInBuilder constructor.
     */
    public function __construct()
    {
        $this->reset();
    }

    public function build()
    {
        $event = new StudentHasCheckedIn(AttendanceId::fromLiteral(static::$nextId));
        $this->reset();

        return $event;
    }

    private function reset()
    {
        static::$nextId++;
    }
}
