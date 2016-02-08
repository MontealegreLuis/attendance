<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup;

use Codeup\DataBuilders\A;
use DateTime;
use PhpSpec\ObjectBehavior;

class AttendanceSpec extends ObjectBehavior
{
    function it_should_be_created_as_a_check_in_entry()
    {
        $this->beConstructedThrough('checkIn', [
            $date = new DateTime(),
            A::student()->build(),
        ]);
        $this->onDate()->shouldBe($date);
    }

    function it_should_be_created_as_a_check_out_entry()
    {
        $this->beConstructedThrough('checkOut', [
            $date = new DateTime(),
            A::student()->build(),
        ]);
        $this->onDate()->shouldBe($date);
    }
}
