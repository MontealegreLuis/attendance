<?php

namespace specs\Codeup\Attendance;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceChecker;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\Students;
use Codeup\DataBuilders\A;
use DateInterval;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UpdateStudentsCheckoutSpec extends ObjectBehavior
{
    /** @var DateTimeImmutable */
    private $now;

    function let()
    {
        $this->now = (new DateTimeImmutable('now'))->setTime(12, 0, 0);
    }

    function it_updates_attendance_for_students_who_have_already_checked_in(
        Students $students,
        AttendanceChecker $checker,
        Attendances $attendances
    ) {
        // Given
        $bootcamp = A::bootcamp()->notYetFinished($this->now)->build();
        $addresses = [
            A::macAddress()->build(),
            A::macAddress()->build(),
            A::macAddress()->build(),
        ];
        $anHourAgo = $this->now->sub(new DateInterval('PT1H'));
        $studentsInClassroom = [
            A::student()->enrolledOn($bootcamp)->whoCheckedInAt($anHourAgo)->build(),
            A::student()->enrolledOn($bootcamp)->whoCheckedInAt($anHourAgo)->build(),
            A::student()->enrolledOn($bootcamp)->whoCheckedInAt($anHourAgo)->build(),
        ];
        $checker->whoIsConnected()->willReturn($addresses);
        $students
            ->attending($this->now, $addresses)
            ->willReturn($studentsInClassroom)
        ;
        $attendances
            ->nextAttendanceId()
            ->willReturn(
                AttendanceId::fromLiteral(1),
                AttendanceId::fromLiteral(2),
                AttendanceId::fromLiteral(3)
            )
        ;

        // Then
        $attendances
            ->add(Argument::type(Attendance::class))
            ->shouldBeCalledTimes(3)
        ;

        // When
        $this->beConstructedWith($checker, $students, $attendances);
        $this->updateStudentsCheckout($this->now)->shouldHaveCount(3);
    }

    function it_updates_only_the_students_who_are_already_checked_in(
        AttendanceChecker $checker,
        Students $students,
        Attendances $attendances
    ) {
        // Given
        $bootcamp = A::bootcamp()->notYetFinished($this->now)->build();
        $addresses = [
            A::macAddress()->build(),
            A::macAddress()->build(),
            A::macAddress()->build(),
        ];
        $anHourAgo = $this->now->sub(new DateInterval('PT1H'));
        $studentsInClassroom = [
            A::student()->enrolledOn($bootcamp)->whoCheckedInAt($anHourAgo)->build(),
            A::student()->enrolledOn($bootcamp)->build(),
            A::student()->enrolledOn($bootcamp)->whoCheckedInAt($anHourAgo)->build(),
        ];
        $checker->whoIsConnected()->willReturn($addresses);
        $students
            ->attending($this->now, $addresses)
            ->willReturn($studentsInClassroom)
        ;
        $attendances
            ->nextAttendanceId()
            ->willReturn(
                AttendanceId::fromLiteral(1),
                AttendanceId::fromLiteral(2)
            )
        ;

        $this->beConstructedWith($checker, $students, $attendances);
        //$this->setPublisher(new EventPublisher());

        // Then
        $attendances
            ->add(Argument::type(Attendance::class))
            ->shouldBeCalledTimes(2)
        ;

        // When
        $this->updateStudentsCheckout($this->now)->shouldHaveCount(2);
    }

    function it_does_not_update_students_if_they_have_not_already_checked_in(
        AttendanceChecker $checker,
        Students $students,
        Attendances $attendances
    ) {
        // Given
        $bootcamp = A::bootcamp()->notYetFinished($this->now)->build();
        $addresses = [
            A::macAddress()->build(),
            A::macAddress()->build(),
            A::macAddress()->build(),
        ];
        $studentsInClassroom = [
            A::student()->enrolledOn($bootcamp)->build(),
            A::student()->enrolledOn($bootcamp)->build(),
            A::student()->enrolledOn($bootcamp)->build(),
        ];
        $checker->whoIsConnected()->willReturn($addresses);
        $students
            ->attending($this->now, $addresses)
            ->willReturn($studentsInClassroom)
        ;

        $this->beConstructedWith($checker, $students, $attendances);

        // When
        $this->updateStudentsCheckout($this->now)->shouldHaveCount(0);

        // Then
        $attendances
            ->add(Argument::type(Attendance::class))
            ->shouldNotHaveBeenCalled()
        ;
    }
}
