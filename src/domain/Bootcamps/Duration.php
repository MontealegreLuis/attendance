<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

class Duration
{
    /** @var DateTime */
    private $startDate;

    /** @var DateTime */
    private $stopDate;

    /**
     * @param DateTime $startDate
     * @param DateTime $stopDate
     */
    private function __construct(DateTime $startDate, DateTime $stopDate)
    {
        AssertValueIs::greaterThan(
            $stopDate,
            $startDate,
            "{$startDate->format('Y-m-d')} is greater than {$stopDate->format('Y-m-d')}"
        );
        $this->startDate = $startDate->setTime(0, 0, 0);
        $this->stopDate = $stopDate->setTime(0, 0, 0);
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $stopDate
     * @return Duration
     */
    public static function between(DateTime $startDate, DateTime $stopDate)
    {
        return new Duration($startDate, $stopDate);
    }

    /**
     * @param DateTime $aDate
     * @return Duration
     */
    public static function workWeekContaining(DateTime $aDate)
    {
        return new Duration(
            clone $aDate->modify('monday this week'),
            $aDate->modify('friday this week')
        );
    }

    /**
     * @param DateTime $aDate
     * @return bool
     */
    public function contains(DateTime $aDate)
    {
        return $aDate >= $this->startDate && $aDate <= $this->stopDate;
    }

    /**
     * @return DateTime
     */
    public function startDate()
    {
        return $this->startDate;
    }

    /**
     * @return DateTime
     */
    public function stopDate()
    {
        return $this->stopDate;
    }
}
