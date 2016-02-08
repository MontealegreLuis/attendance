<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup;

use DateTime;
use PhpSpec\ObjectBehavior;

class AttendanceSpec extends ObjectBehavior
{
    function it_is_should_be_created_as_a_check_in_entry()
    {
        $this->beConstructedThrough('checkIn', [$date = new DateTime()]);
        $this->onDate()->shouldBe($date);
    }

    function it_is_should_be_created_as_a_check_out_entry()
    {
        $this->beConstructedThrough('checkOut', [$date = new DateTime()]);
        $this->onDate()->shouldBe($date);
    }
}
