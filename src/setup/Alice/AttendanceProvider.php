<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Alice;

use Codeup\Bootcamps\Attendances;
use Codeup\DomainEvents\EventStore;
use Codeup\Messaging\MessageTracker;
use Faker\Provider\Base;

class AttendanceProvider extends Base
{
    /** @var EventStore */
    private $events;

    /** @var MessageTracker */
    private $messages;

    /** @var Attendances */
    private $attendances;

    /**
     * @param EventStore $events
     * @param MessageTracker $messages
     * @param Attendances $attendances
     */
    public function __construct(
        EventStore $events,
        MessageTracker $messages,
        Attendances $attendances
    ) {
        $this->events = $events;
        $this->messages = $messages;
        $this->attendances = $attendances;
    }

    /**
     * @return int
     */
    public function eventId()
    {
        return $this->events->nextEventId()->value();
    }

    /**
     * @return int
     */
    public function messageId()
    {
        return $this->messages->nextMessageId();
    }

    /**
     * @return int
     */
    public function attendanceId()
    {
        return $this->attendances->nextAttendanceId()->value();
    }
}
