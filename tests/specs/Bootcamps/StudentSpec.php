<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\MacAddress;
use DateTime;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StudentSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('attend', [
            Bootcamp::start(new DateTime(), 'Hampton'),
            'Luis Montealegre',
            MacAddress::withValue('00-80-C8-E3-4C-BD'),
        ]);
    }

    function it_should_start_attending_a_bootcamp()
    {
        $this->name()->shouldBe('Luis Montealegre');
    }

    function it_should_be_able_to_check_in()
    {
        $this->checkIn(new DateTime());
        $this->hasCheckedIn()->shouldBe(true);
    }

    function it_should_be_able_to_check_out()
    {
        $this->checkOut(new DateTime());
        $this->hasCheckedOut()->shouldBe(true);
    }
}
