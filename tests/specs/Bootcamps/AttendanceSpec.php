<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\StudentId;
use DateTime;
use PhpSpec\ObjectBehavior;

class AttendanceSpec extends ObjectBehavior
{
    function it_knows_it_is_a_check_in_entry()
    {
        $this->beConstructedThrough('checkIn', [
            AttendanceId::fromLiteral(1),
            new DateTime(),
            StudentId::fromLiteral(1),
        ]);
        $this->isCheckIn()->shouldBe(true);
    }

    function it_knows_it_is_a_check_out_entry()
    {
        $this->beConstructedThrough('checkOut', [
            AttendanceId::fromLiteral(1),
            $date = new DateTime(),
            StudentId::fromLiteral(1),
        ]);
        $this->occurredOn($date)->shouldBe(true);
    }

    function it_knows_when_it_occurred_on_a_different_day()
    {
        $this->beConstructedThrough('checkOut', [
            AttendanceId::fromLiteral(1),
            new DateTime('now'),
            StudentId::fromLiteral(1),
        ]);
        $this->occurredOn(new DateTime('1 day ago'))->shouldBe(false);
        $this->occurredOn(new DateTime('tomorrow'))->shouldBe(false);
    }
}
