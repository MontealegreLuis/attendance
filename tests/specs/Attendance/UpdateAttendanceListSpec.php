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
        Attendances $attendances,
        EventStream $stream
    ) {
        $attendance = [
            'bootcamp_id' => 1,
            'cohort_name' => 'Hampton',
            'start_date' => '2016-01-30 00:00:00',
            'stop_date' => '2016-04-30 00:00:00',
            'start_time' => '0000-00-00 09:00:00',
            'stop_time' => '0000-00-00 16:00:00',
            'student_id' => 2,
            'name' => 'Luis Montealegre',
            'mac_address' => '00-80-C8-E3-4C-BD',
            'attendance_id' => 3,
            'date' => '2016-04-03 08:35:09',
            'type' => Attendance::CHECK_IN,
        ];

        $attendances
            ->detailsOf(AttendanceId::fromLiteral($attendance['attendance_id']))
            ->willReturn($attendance)
        ;

        $this->beConstructedWith(
            $stream,
            $attendances,
            new JsonSerializer()
        );

        $this->consume(new StoredEvent(
            StoredEventId::fromLiteral(1),
            json_encode(['attendance_id' => 3]),
            StudentHasCheckedIn::class,
            new DateTime('now')
        ));

        $stream
            ->push('{"bootcamp_id":1,"cohort_name":"Hampton","start_date":"2016-01-30 00:00:00","stop_date":"2016-04-30 00:00:00","start_time":"0000-00-00 09:00:00","stop_time":"0000-00-00 16:00:00","student_id":2,"name":"Luis Montealegre","mac_address":"00-80-C8-E3-4C-BD","attendance_id":3,"date":"2016-04-03 08:35:09","type":0}')
            ->shouldHaveBeenCalled()
        ;
    }
}
