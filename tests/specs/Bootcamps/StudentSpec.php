<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Schedule;
use Codeup\Bootcamps\Duration;
use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\StudentId;
use DateTime;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StudentSpec extends ObjectBehavior
{
    /** @var DateTimeImmutable */
    private $now;

    function let()
    {
        $this->now = new DateTimeImmutable('now');
        $currentHour = (int) $this->now->format('H');
        $currentMinute = (int) $this->now->format('i');

        $this->beConstructedThrough('attend', [
            StudentId::fromLiteral(1),
            Bootcamp::start(
                BootcampId::fromLiteral(1),
                Duration::between(
                    $this->now->modify('-1 day'),
                    $this->now->modify('30 days')
                ),
                'Hampton',
                Schedule::withClassTimeBetween(
                    $this->now->setTime($currentHour - 1, $currentMinute),
                    $this->now->setTime($currentHour + 6, $currentMinute)
                )
            ),
            'Luis Montealegre',
            MacAddress::withValue('00-80-C8-E3-4C-BD'),
        ]);
    }

    function it_knows_when_a_bootcamp_has_ended()
    {
        $this->isInClass(new DateTime('31 days'))->shouldBe(false);
    }

    function it_knows_when_a_bootcamp_is_in_progress()
    {
        $this->isInClass(new DateTime('10 days'))->shouldBe(true);
    }

    function it_is_able_to_check_in()
    {
        $this->register(Attendance::checkIn(
            AttendanceId::fromLiteral(1),
            $today = new DateTime(),
            StudentId::fromLiteral(1)
        ));
        $this->hasCheckedIn($today)->shouldBe(true);
    }

    function it_is_able_to_check_out()
    {
        $this->register(Attendance::checkOut(
            AttendanceId::fromLiteral(1),
            $today = new DateTime(),
            StudentId::fromLiteral(1)
        ));
        $this->hasCheckedOut($today)->shouldBe(true);
    }
}
