<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Codeup\Bootcamps\BootcampSchedule;
use DateTime;
use PhpSpec\ObjectBehavior;

class BootcampSpec extends ObjectBehavior
{
    function it_should_have_a_name()
    {
        $this->beConstructedThrough('start', [
            new DateTime(),
            'Hampton',
            BootcampSchedule::withClassTimeBetween(
                new DateTime('-6 hour'),
                new DateTime('now')
            )
        ]);
        $this->cohortName()->shouldBe('Hampton');
    }
}
