<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

class BootcampSchedule
{
    /** @var DateTime */
    private $startTime;

    /** @var DateTime */
    private $stopTime;

    /**
     * @param DateTime $startTime
     * @param DateTime $stopTime
     */
    private function __construct(DateTime $startTime, DateTime $stopTime)
    {
        AssertValueIs::greaterThan($stopTime, $startTime);
        $this->startTime = $startTime;
        $this->stopTime = $stopTime;
    }

    /**
     * @param DateTime $startTime
     * @param DateTime $stopTime
     * @return BootcampSchedule
     */
    public static function withClassTimeBetween(
        DateTime $startTime,
        DateTime $stopTime
    ) {
        return new BootcampSchedule($startTime, $stopTime);
    }

    /**
     * @return DateTime
     */
    public function startTime()
    {
        return $this->startTime;
    }

    /**
     * @return DateTime
     */
    public function stopTime()
    {
        return $this->stopTime;
    }
}
