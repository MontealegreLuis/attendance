<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

class Schedule
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
        $this->startTime = $startTime->setDate(0, 1, 1);
        $this->stopTime = $stopTime->setDate(0, 1, 1);
    }

    /**
     * @param DateTime $startTime
     * @param DateTime $stopTime
     * @return Schedule
     */
    public static function withClassTimeBetween(
        DateTime $startTime,
        DateTime $stopTime
    ) {
        return new Schedule($startTime, $stopTime);
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
