<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;
use Codeup\DomainEvents\Event;

class StudentHasCheckedIn implements Event
{
    /** @var DateTime */
    private $occurredOn;

    /** @var AttendanceId */
    private $attendanceId;

    /**
     * @param AttendanceId $attendanceId
     */
    public function __construct(AttendanceId $attendanceId)
    {
        $this->attendanceId = $attendanceId;
        $this->occurredOn = new DateTime('now');
    }

    /**
     * @return AttendanceId
     */
    public function attendanceId()
    {
        return $this->attendanceId;
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
