<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Codeup\Bootcamps\Schedule;
use Codeup\Bootcamps\Duration;
use DateTime;
use PhpSpec\ObjectBehavior;

class BootcampSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('start', [
            Duration::between(new DateTime('-30 day'), new DateTime('30 day')),
            'Hampton',
            Schedule::withClassTimeBetween(
                new DateTime('-6 hour'),
                new DateTime('now')
            )
        ]);
    }

    function it_should_have_a_name()
    {
        $this->cohortName()->shouldBe('Hampton');
    }

    function it_should_know_if_it_is_in_progress()
    {
        $this->isInProgress(new DateTime('now'))->shouldBe(true);
    }
}
