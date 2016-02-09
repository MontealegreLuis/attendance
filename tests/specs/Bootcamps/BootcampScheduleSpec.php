<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use DateTime;
use PhpSpec\ObjectBehavior;

class BootcampScheduleSpec extends ObjectBehavior
{
    function it_should_be_created_with_a_valid_time_range()
    {
        $this->beConstructedThrough('withClassTimeBetween', [
            $now = new DateTime('now'),
            new DateTime('+ 7 day'),
        ]);
        $this->startTime()->shouldBe($now);
    }
}
