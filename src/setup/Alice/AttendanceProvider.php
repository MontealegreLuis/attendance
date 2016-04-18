<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Alice;

use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\Bootcamps;
use Codeup\Bootcamps\Duration;
use Codeup\Bootcamps\Students;
use Codeup\DomainEvents\EventStore;
use Codeup\Messaging\MessageTracker;
use DateTime;
use Faker\Provider\Base;

class AttendanceProvider extends Base
{
    /** @var EventStore */
    private $events;

    /** @var MessageTracker */
    private $messages;

    /** @var Attendances */
    private $attendances;

    /** @var Bootcamps */
    private $bootcamps;

    /** @var Students */
    private $students;

    /**
     * @param EventStore $events
     * @param MessageTracker $messages
     * @param Attendances $attendances
     * @param Bootcamps $bootcamps
     * @param Students $students
     */
    public function __construct(
        EventStore $events,
        MessageTracker $messages,
        Attendances $attendances,
        Bootcamps $bootcamps,
        Students $students
    ) {
        $this->events = $events;
        $this->messages = $messages;
        $this->attendances = $attendances;
        $this->bootcamps = $bootcamps;
        $this->students = $students;
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
    public function bootcampId()
    {
        return $this->bootcamps->nextBootcampId()->value();
    }

    /**
     * @return int
     */
    public function studentId()
    {
        return $this->students->nextStudentId()->value();
    }

    /**
     * @return int
     */
    public function attendanceId()
    {
        return $this->attendances->nextAttendanceId()->value();
    }

    /**
     * @return Duration
     */
    public function startingYesterday()
    {
        return Duration::between(
            new DateTime('yesterday'),
            new DateTime('4 months')
        );
    }

    /**
     * @return Duration
     */
    public function finishingYesterday()
    {
        return Duration::between(
            new DateTime('4 months ago'),
            new DateTime('yesterday')
        );
    }
}
