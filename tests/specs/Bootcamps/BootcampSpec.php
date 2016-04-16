<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Schedule;
use Codeup\Bootcamps\Duration;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;

class BootcampSpec extends ObjectBehavior
{
    /** @var DateTimeImmutable */
    private $now;

    function let()
    {
        $this->now = new DateTimeImmutable('now');
        $currentHour = (int) $this->now->format('H');
        $currentMinute = (int) $this->now->format('i');

        $this->beConstructedThrough('start', [
            BootcampId::fromLiteral(1),
            Duration::between(
                $this->now->modify('-1 day'),
                $this->now->modify('4 months')
            ),
            'Hampton',
            Schedule::withClassTimeBetween(
                $this->now->setTime($currentHour - 1, $currentMinute),
                $this->now->setTime($currentHour + 6, $currentMinute)
            )
        ]);
    }

    function it_knows_if_it_is_in_progress()
    {
        $this->isInProgress($this->now)->shouldBe(true);
    }
}
