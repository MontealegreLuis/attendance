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
        $currentMinute = (int) $this->now->format('i');
        $currentHour = (int) $this->now->format('G');
        if ($currentHour >= 16) {
            $currentHour -= 7;
            $this->now = $this->now->setTime($currentHour, $currentMinute);
        }

        $this->beConstructedThrough('start', [
            BootcampId::fromLiteral(1),
            Duration::between(
                $this->now->modify('1 day ago'),
                $this->now->modify('4 months')
            ),
            'Hampton',
            Schedule::withClassTimeBetween(
                $this->now,
                $this->now->setTime($currentHour + 7, $currentMinute)
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
