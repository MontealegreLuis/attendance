<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Attendance;

use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\StudentHasCheckedIn;
use Codeup\Bootcamps\StudentId;
use Codeup\DataBuilders\A as An;
use Codeup\DomainEvents\StoredEvent;
use Codeup\DomainEvents\StoredEventId;
use Codeup\JmsSerializer\JsonSerializer;
use Codeup\ServerSentEvents\EventStream;
use DateTime;
use PhpSpec\ObjectBehavior;

class UpdateAttendanceListSpec extends ObjectBehavior
{
    function it_updates_the_attendance_list(
        Attendances $attendances,
        EventStream $stream
    ) {
        $attendance = An::attendance()
            ->withId(1)
            ->withStudentId(StudentId::fromLiteral(2))
            ->recordedAt(DateTime::createFromFormat(
                'Y-m-d H:i:s', '2016-03-30 08:30:03'
            ))
            ->build()
        ;
        $attendances->with($attendance->id())->willReturn($attendance);

        $this->beConstructedWith(
            $stream,
            $attendances,
            new JsonSerializer()
        );

        $this->consume(new StoredEvent(
            StoredEventId::fromLiteral(1),
            json_encode(['attendance_id' => 1]),
            StudentHasCheckedIn::class,
            new DateTime('now')
        ));

        $stream
            ->push('{"attendance_id":1,"date":"2016-03-30 08:30:03","type":0,"student_id":2}')
            ->shouldHaveBeenCalled()
        ;
    }
}
