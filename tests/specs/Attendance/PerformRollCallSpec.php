<?php

namespace specs\Codeup\Attendance;

use Codeup\Bootcamps\AttendanceChecker;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\Students;
use Codeup\DataBuilders\A;
use DateTime;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PerformRollCallSpec extends ObjectBehavior
{
    function it_should_update_all_students_who_have_arrived_now(
        AttendanceChecker $checker,
        Students $students
    ) {
        // Given
        $addresses = [
            $address1 = A::macAddress()->build(),
            $address2 = A::macAddress()->build(),
            $address3 = A::macAddress()->build(),
        ];
        $checker->whoIsConnected()->willReturn($addresses);
        $students->attending(new DateTime(), $addresses)->willReturn([
            $student1 = A::student()->withMacAddress($address1)->build(),
            $student2 = A::student()->withMacAddress($address2)->build(),
            $student3 = A::student()->withMacAddress($address3)->build(),
        ]);

        // Then
        $students->update($student1)->shouldBeCalled();
        $students->update($student2)->shouldBeCalled();
        $students->update($student3)->shouldBeCalled();

        $this->beConstructedWith($checker, $students);

        // When
        $this->rollCall();
    }

    function it_should_update_only_the_students_who_have_not_already_checked_in(
        AttendanceChecker $checker,
        Students $students
    ) {
        // Given
        $addresses = [
            $address1 = A::macAddress()->build(),
            $address2 = A::macAddress()->build(),
            $address3 = A::macAddress()->build(),
        ];
        $checker->whoIsConnected()->willReturn($addresses);
        $students->attending(new DateTime(), $addresses)->willReturn([
            $student1 = A::student()->withMacAddress($address1)->build(),
            $student2 = A::student()
                ->whoCheckedInAt(new DateTime('-1 hour'))
                ->withMacAddress($address2)
                ->build(),
            $student3 = A::student()->withMacAddress($address3)->build(),
        ]);

        // Then
        $students->update($student1)->shouldBeCalled();
        $students->update($student3)->shouldBeCalled();

        $this->beConstructedWith($checker, $students);

        // When
        $this->rollCall();
    }

    function it_should_not_update_students_who_have_already_checked_in(
        AttendanceChecker $checker,
        Students $students
    ) {
        // Given
        $addresses = [
            $address1 = A::macAddress()->build(),
            $address2 = A::macAddress()->build(),
            $address3 = A::macAddress()->build(),
        ];
        $checker->whoIsConnected()->willReturn($addresses);
        $students->attending(new DateTime(), $addresses)->willReturn([
            $student1 = A::student()
                ->whoCheckedInAt(new DateTime('-2 hour'))
                ->withMacAddress($address1)
                ->build(),
            $student2 = A::student()
                ->whoCheckedInAt(new DateTime('-1 hour'))
                ->withMacAddress($address2)
                ->build(),
            $student3 = A::student()
                ->whoCheckedInAt(new DateTime('-3 hour'))
                ->withMacAddress($address3)->build(),
        ]);

        // Then
        $students->update($student1)->shouldNotBeCalled();
        $students->update($student2)->shouldNotBeCalled();
        $students->update($student3)->shouldNotBeCalled();

        $this->beConstructedWith($checker, $students);

        // When
        $this->rollCall();
    }
}
