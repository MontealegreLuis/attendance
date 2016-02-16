<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Schedule;
use Codeup\Bootcamps\Duration;
use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\StudentId;
use DateTime;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StudentSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('attend', [
            StudentId::fromLiteral(1),
            Bootcamp::start(
                BootcampId::fromLiteral(1),
                Duration::between(new DateTime('-30 day'), new DateTime('30 day')),
                'Hampton',
                Schedule::withClassTimeBetween(
                    new DateTime('-6 hour'),
                    new DateTime('now')
                )
            ),
            'Luis Montealegre',
            MacAddress::withValue('00-80-C8-E3-4C-BD'),
        ]);
    }

    function it_should_start_attending_a_bootcamp()
    {
        $this->name()->shouldBe('Luis Montealegre');
    }

    function it_should_know_when_a_bootcamp_has_ended()
    {
        $this->isInClass(new DateTime('31 days'))->shouldBe(false);
    }

    function it_should_know_when_a_bootcamp_is_in_progress()
    {
        $this->isInClass(new DateTime('10 days'))->shouldBe(true);
    }

    function it_should_be_able_to_check_in()
    {
        $this->checkIn($today = new DateTime());
        $this->hasCheckedIn($today)->shouldBe(true);
    }

    function it_should_be_able_to_check_out()
    {
        $this->checkOut($today = new DateTime());
        $this->hasCheckedOut($today)->shouldBe(true);
    }
}
