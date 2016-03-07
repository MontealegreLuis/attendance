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
use Codeup\Messaging\MessageConsumer;
use Igorw\EventSource\Stream;

class UpdateAttendanceList implements MessageConsumer
{
    /** @var Stream */
    private $stream;

    /** @var Attendances */
    private $attendances;

    /**
     * @param Stream $stream
     * @param Attendances $attendances
     */
    public function __construct(Stream $stream, Attendances $attendances)
    {
        $this->stream = $stream;
        $this->attendances = $attendances;
    }

    /**
     * @param StoredEvent $event
     */
    public function consume(StoredEvent $event)
    {
        $eventInformation = json_decode($event->body(), true);
        $attendance = $this->attendances->detailsOf(
            AttendanceId::fromLiteral($eventInformation['attendance_id']['value'])
        );
        $this
            ->stream
               ->event()
                    ->setData(json_encode($attendance))
                ->end()
            ->flush()
        ;
    }
}
