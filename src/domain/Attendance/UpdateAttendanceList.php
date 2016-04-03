<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Attendances;
use Codeup\DomainEvents\StoredEvent;
use Codeup\Serializer\Serializer;
use Codeup\ServerSentEvents\EventStream;
use Codeup\Messaging\MessageConsumer;

class UpdateAttendanceList implements MessageConsumer
{
    /** @var EventStream */
    private $stream;

    /** @var Attendances */
    private $attendances;

    /** @var Serializer */
    private $serializer;

    /**
     * @param EventStream $stream
     * @param Attendances $attendances
     * @param Serializer $serializer
     */
    public function __construct(
        EventStream $stream,
        Attendances $attendances,
        Serializer $serializer
    ) {
        $this->stream = $stream;
        $this->attendances = $attendances;
        $this->serializer = $serializer;
    }

    /**
     * @param StoredEvent $aStudentHasCheckedIn
     */
    public function consume(StoredEvent $aStudentHasCheckedIn)
    {
        $event = $this->serializer->deserialize($aStudentHasCheckedIn->body());
        $attendance = $this->attendances->detailsOf(
            AttendanceId::fromLiteral($event['attendance_id'])
        );
        $this->stream->push($this->serializer->serialize($attendance));
    }
}
