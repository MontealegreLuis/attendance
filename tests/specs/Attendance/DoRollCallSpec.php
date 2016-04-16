<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Attendance;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceChecker;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\Students;
use Codeup\DataBuilders\A;
use Codeup\DomainEvents\EventPublisher;
use DateInterval;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoRollCallSpec extends ObjectBehavior
{
    /** @var DateTimeImmutable */
    private $now;

    function let()
    {
        $this->now = (new DateTimeImmutable('now'))->setTime(12, 0, 0);
    }

    function it_updates_all_students_who_have_arrived_now(
        AttendanceChecker $checker,
        Students $students,
        Attendances $attendances
    ) {
        // Given
        $addresses = [
            $address1 = A::macAddress()->build(),
            $address2 = A::macAddress()->build(),
            $address3 = A::macAddress()->build(),
        ];
        $bootcamp = A::bootcamp()->notYetFinished($this->now)->build();
        $checker->whoIsConnected()->willReturn($addresses);
        $students->attending($this->now, $addresses)->willReturn([
            $student1 = A::student()
                ->enrolledOn($bootcamp)
                ->withMacAddress($address1)
                ->build(),
            $student2 = A::student()
                ->enrolledOn($bootcamp)
                ->withMacAddress($address2)
                ->build(),
            $student3 = A::student()
                ->enrolledOn($bootcamp)
                ->withMacAddress($address3)
                ->build(),
        ]);
        $this->beConstructedWith($checker, $students, $attendances);
        $this->setPublisher(new EventPublisher());

        // Then
        $attendances
            ->nextAttendanceId()
            ->willReturn(
                AttendanceId::fromLiteral(1),
                AttendanceId::fromLiteral(2),
                AttendanceId::fromLiteral(3)
            )
        ;
        $attendances
            ->add(Argument::type(Attendance::class))
            ->shouldBeCalledTimes(3)
        ;

        // When
        $this->rollCall($this->now);
    }

    function it_updates_only_the_students_who_have_not_already_checked_in(
        AttendanceChecker $checker,
        Students $students,
        Attendances $attendances
    ) {
        // Given
        $addresses = [
            $address1 = A::macAddress()->build(),
            $address2 = A::macAddress()->build(),
            $address3 = A::macAddress()->build(),
        ];
        $bootcamp = A::bootcamp()->notYetFinished($this->now)->build();
        $checker->whoIsConnected()->willReturn($addresses);
        $students->attending($this->now, $addresses)->willReturn([
            $student1 = A::student()
                ->enrolledOn($bootcamp)
                ->withMacAddress($address1)->build(),
            $student2 = A::student()
                ->enrolledOn($bootcamp)
                ->whoCheckedInAt($this->now->sub(new DateInterval('PT1H')))
                ->withMacAddress($address2)
                ->build(),
            $student3 = A::student()
                ->enrolledOn($bootcamp)
                ->withMacAddress($address3)
                ->build(),
        ]);
        $this->beConstructedWith($checker, $students, $attendances);
        $this->setPublisher(new EventPublisher());

        // Then
        $attendances
            ->nextAttendanceId()
            ->willReturn(
                AttendanceId::fromLiteral(1),
                AttendanceId::fromLiteral(2)
            )
        ;
        $attendances
            ->add(Argument::type(Attendance::class))
            ->shouldBeCalledTimes(2)
        ;

        // When
        $this->rollCall($this->now);
    }

    function it_does_not_update_students_who_have_already_checked_in(
        AttendanceChecker $checker,
        Students $students,
        Attendances $attendances
    ) {
        // Given
        $addresses = [
            $address1 = A::macAddress()->build(),
            $address2 = A::macAddress()->build(),
            $address3 = A::macAddress()->build(),
        ];
        $bootcamp = A::bootcamp()->notYetFinished($this->now)->build();
        $checker->whoIsConnected()->willReturn($addresses);
        $students->attending($this->now, $addresses)->willReturn([
            $student1 = A::student()
                ->enrolledOn($bootcamp)
                ->whoCheckedInAt($this->now->sub(new DateInterval('PT2H')))
                ->withMacAddress($address1)
                ->build(),
            $student2 = A::student()
                ->enrolledOn($bootcamp)
                ->whoCheckedInAt($this->now->sub(new DateInterval('PT1H')))
                ->withMacAddress($address2)
                ->build(),
            $student3 = A::student()
                ->enrolledOn($bootcamp)
                ->whoCheckedInAt($this->now->sub(new DateInterval('PT3H')))
                ->withMacAddress($address3)->build(),
        ]);
        $this->beConstructedWith($checker, $students, $attendances);

        // When
        $this->rollCall($this->now);

        // Then
        $attendances
            ->add(Argument::type(Attendance::class))
            ->shouldNotHaveBeenCalled()
        ;
    }
}
