<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup;

use Codeup\Bootcamp;
use Codeup\MacAddress;
use DateTime;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StudentSpec extends ObjectBehavior
{
    function it_should_start_attending_a_bootcamp()
    {
        $this->beConstructedThrough('attend', [
            Bootcamp::start(new DateTime(), 'Hampton'),
            'Luis Montealegre',
            MacAddress::withValue('00-80-C8-E3-4C-BD'),
        ]);
    }
}
