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
    function it_should_be_created_with_a_valid_date_range()
    {
        $this->beConstructedThrough('between', [
            new DateTime('-30 day'),
            new DateTime('30 day'),
        ]);
        $this->contains(new DateTime('now'))->shouldBe(true);
    }

    function it_should_not_be_created_with_an_invalid_date_range()
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

    function it_should_know_when_a_date_is_after_duration_stop_date()
    {
        $this->beConstructedThrough('between', [
            new DateTime('-30 day'),
            new DateTime('30 day'),
        ]);
        $this->contains(new DateTime('31 day'))->shouldBe(false);
    }

    function it_should_know_when_a_date_is_before_duration_start_date()
    {
        $this->beConstructedThrough('between', [
            new DateTime('-30 day'),
            new DateTime('30 day'),
        ]);
        $this->contains(new DateTime('-31 day'))->shouldBe(false);
    }
}
