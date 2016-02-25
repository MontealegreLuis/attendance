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
use DateTimeImmutable;
use Faker\Factory;

class BootcampBuilder
{
    /** @var \Faker\Generator */
    private $factory;

    /** @var int */
    private static $nextId = 0;

    /** @var Duration */
    private $duration;

    /** @var string */
    private $cohortName;

    /** @var Schedule */
    private $schedule;

    /**
     * BootcampBuilder constructor.
     */
    public function __construct()
    {
        $this->factory = Factory::create();
        $this->reset();
    }

    /**
     * @return Bootcamp
     */
    public function build()
    {
        return Bootcamp::start(
            BootcampId::fromLiteral(static::$nextId),
            $this->duration,
            $this->cohortName,
            $this->schedule
        );
    }

    /**
     * @return BootcampBuilder
     */
    public function alreadyFinished()
    {
        $this->duration = Duration::between(
            $this->factory->dateTimeBetween('-7 day', '-3 day'),
            $this->factory->dateTimeBetween('-2 day', '-1 day')
        );

        return $this;
    }

    private function reset()
    {
        static::$nextId++;
        $this->duration = Duration::between(
            $this->factory->dateTimeBetween('-7 day', '-1 day'),
            $this->factory->dateTimeBetween('1 day', '7 day')
        );
        $this->cohortName = $this->factory->word;
        $aDate = (new DateTimeImmutable())->setTime(12, 0, 0);
        $hours = $this->factory->numberBetween(1,10);
        $this->schedule = Schedule::withClassTimeBetween(
            $aDate->sub(new DateInterval("PT{$hours}H")),
            $aDate
        );
    }
}
