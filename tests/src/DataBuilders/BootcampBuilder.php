<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Duration;
use Codeup\Bootcamps\Schedule;
use DateInterval;
use DateTimeInterface;
use DateTimeImmutable;

class BootcampBuilder
{
    use ProvidesFakeDataGenerator;

    /** @var int */
    private static $nextId = 0;

    /** @var Duration */
    private $duration;

    /** @var string */
    private $cohortName;

    /** @var Schedule */
    private $schedule;

    /**
     * Set initial random values
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * @return Bootcamp
     */
    public function build()
    {
        $bootcamp = Bootcamp::start(
            BootcampId::fromLiteral(static::$nextId),
            $this->duration,
            $this->cohortName,
            $this->schedule
        );

        $this->reset();

        return $bootcamp;
    }

    /**
     * It creates a bootcamp that started 4 months before and finished 1 day
     * **after** `$byNow` date
     *
     * @param DateTimeInterface $byNow
     * @return BootcampBuilder
     */
    public function notYetFinished(DateTimeInterface $byNow)
    {
        $tomorrow = $byNow->modify('tomorrow');
        $fourMonthsAgo = $byNow->modify('4 months ago');

        $this->duration = Duration::between($fourMonthsAgo, $tomorrow);

        return $this;
    }

    /**
     * It creates a bootcamp that started 4 months before and finished 1 day
     * **before** `$byNow` date
     *
     * @param DateTimeInterface $byNow
     * @return BootcampBuilder
     */
    public function alreadyFinished(DateTimeInterface $byNow)
    {
        $yesterday = $byNow->modify('1 day ago');
        $fourMonthsAgo = $byNow->modify('4 months ago');
        $this->duration = Duration::between($fourMonthsAgo, $yesterday);

        return $this;
    }

    private function reset()
    {
        static::$nextId++;
        $this->cohortName = $this->generator()->word;
        $aDate = (new DateTimeImmutable())->setTime(12, 0, 0);
        $hours = $this->generator()->numberBetween(1,10);
        $this->schedule = Schedule::withClassTimeBetween(
            $aDate->sub(new DateInterval("PT{$hours}H")),
            $aDate
        );
    }
}
