<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Attendance;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\StudentHasCheckedIn;
use Codeup\DomainEvents\StoredEvent;
use Codeup\DomainEvents\StoredEventId;
use Codeup\JmsSerializer\JsonSerializer;
use Codeup\ServerSentEvents\EventStream;
use DateTime;
use PhpSpec\ObjectBehavior;

class UpdateAttendanceListSpec extends ObjectBehavior
{
    function it_updates_the_attendance_list(
        EventStream $stream,
        Attendances $attendances
    ) {
        $bootcampSummary = [
            'bootcamp_id' => 1,
            'students_count' => 12,
            'on_time_students_count' => 3,
        ];
        $attendanceId = AttendanceId::fromLiteral(3);

        $attendances
            ->summary($attendanceId)
            ->willReturn($bootcampSummary)
        ;

        $this->beConstructedWith(
            $stream,
            $attendances,
            new JsonSerializer()
        );

        $this->consume(new StoredEvent(
            StoredEventId::fromLiteral(1),
            json_encode(['attendance_id' => $attendanceId->value()]),
            StudentHasCheckedIn::class,
            new DateTime('now')
        ));

        $stream
            ->push('{"bootcamp_id":1,"students_count":12,"on_time_students_count":3}')
            ->shouldHaveBeenCalled()
        ;
    }
}
