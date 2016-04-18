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
        $currentHour = $this->now->format('G') >= 18
            ? $this->now->format('G') - 12
            : $this->now->format('G')
        ;
        $currentMinute = (int) $this->now->format('i');

        $this->beConstructedThrough('start', [
            BootcampId::fromLiteral(1),
            Duration::between(
                $this->now->modify('1 day ago'),
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

    function it_knows_if_has_not_yet_started()
    {
        $this->isInProgress($this->now->modify('2 days ago'))->shouldBe(false);
    }

    function it_knows_if_has_finished()
    {
        $this->isInProgress($this->now->modify('5 months'))->shouldBe(false);
    }
}
