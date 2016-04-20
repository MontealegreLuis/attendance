<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Assert\InvalidArgumentException;
use DateTime;
use PhpSpec\ObjectBehavior;

class ScheduleSpec extends ObjectBehavior
{
    function it_is_created_with_a_valid_time_range()
    {
        $this->beConstructedThrough('withClassTimeBetween', [
            $now = (new DateTime('now'))->setTime(9, 0, 0),
            $plus7Hours = (new DateTime('now'))->setTime(16, 0, 0),
        ]);
        $this->startTime()->shouldBe($now);
        $this->stopTime()->shouldBe($plus7Hours);
    }

    function it_cannot_be_created_with_an_invalid_time_range()
    {
        $this->beConstructedThrough('withClassTimeBetween', [
            $now = (new DateTime('now'))->setTime(16, 0, 0),
            $sevenHoursBefore = (new DateTime('now'))->setTime(9, 0, 0),
        ]);
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_knows_its_start_and_stop_times()
    {
        $startTime = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '2016-04-19 13:13:05'
        );
        $stopTime = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '2016-04-19 20:13:05'
        );
        $this->beConstructedThrough('withClassTimeBetween', [
            $startTime,
            $stopTime,
        ]);

        $this->startTime()->format('Y-m-d H:i:s')->shouldBe('0000-01-01 13:13:05');
        $this->stopTime()->format('Y-m-d H:i:s')->shouldBe('0000-01-01 20:13:05');
    }
}
