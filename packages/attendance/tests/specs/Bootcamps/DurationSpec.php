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

class DurationSpec extends ObjectBehavior
{
    function it_is_created_with_a_valid_date_range()
    {
        $this->beConstructedThrough('between', [
            new DateTime('-30 day'),
            new DateTime('30 day'),
        ]);
        $this->contains(new DateTime('now'))->shouldBe(true);
    }

    function it_cannot_be_created_with_an_invalid_date_range()
    {
        $this->beConstructedThrough('between', [
            new DateTime('30 day'),
            new DateTime('-30 day'),
        ]);
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_knows_when_a_date_is_after_stop_date()
    {
        $this->beConstructedThrough('between', [
            new DateTime('-30 day'),
            new DateTime('30 day'),
        ]);
        $this->contains(new DateTime('31 day'))->shouldBe(false);
    }

    function it_knows_when_a_date_is_before_start_date()
    {
        $this->beConstructedThrough('between', [
            new DateTime('-30 day'),
            new DateTime('30 day'),
        ]);
        $this->contains(new DateTime('-31 day'))->shouldBe(false);
    }

    function it_knows_its_start_and_stop_dates()
    {
        $startDate = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '2016-01-19 13:13:05'
        );
        $stopDate = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '2016-05-19 20:13:05'
        );
        $this->beConstructedThrough('between', [
            $startDate,
            $stopDate,
        ]);

        $this->startDate()->format('Y-m-d H:i:s')->shouldBe('2016-01-19 00:00:00');
        $this->stopDate()->format('Y-m-d H:i:s')->shouldBe('2016-05-19 00:00:00');
    }
}
