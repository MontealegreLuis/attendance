<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTimeInterface;

/**
 * The usual schedule of a bootcamp is from 9:00 to 16:00 hrs.
 */
class Schedule
{
    /** @var DateTimeInterface */
    private $startTime;

    /** @var DateTimeInterface */
    private $stopTime;

    /**
     * @param DateTimeInterface $startTime
     * @param DateTimeInterface $stopTime
     */
    private function __construct(
        DateTimeInterface $startTime,
        DateTimeInterface $stopTime
    ) {
        AssertValueIs::greaterThan(
            $stopTime->setDate(0, 1, 1),
            $startTime->setDate(0, 1, 1),
            sprintf(
                'Scheduled start time %s is greater than stop time %s',
                $startTime->format('H:i:s'),
                $stopTime->format('H:i:s')
            )
        );
        $this->startTime = $startTime->setDate(0, 1, 1);
        $this->stopTime = $stopTime->setDate(0, 1, 1);
    }

    /**
     * @param DateTimeInterface $startTime
     * @param DateTimeInterface $stopTime
     * @return Schedule
     */
    public static function withClassTimeBetween(
        DateTimeInterface $startTime,
        DateTimeInterface $stopTime
    ) {
        return new Schedule($startTime, $stopTime);
    }

    /**
     * @return DateTimeInterface
     */
    public function startTime()
    {
        return $this->startTime;
    }

    /**
     * @return DateTimeInterface
     */
    public function stopTime()
    {
        return $this->stopTime;
    }
}
