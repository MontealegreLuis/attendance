<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTimeInterface;

/**
 * The duration of a bootcamp is usually of 16 weeks
 */
class Duration
{
    /** @var DateTimeInterface */
    private $startDate;

    /** @var DateTimeInterface */
    private $stopDate;

    /**
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $stopDate
     */
    private function __construct(
        DateTimeInterface $startDate,
        DateTimeInterface $stopDate
    ) {
        AssertValueIs::greaterThan(
            $stopDate,
            $startDate,
            sprintf(
                'Start date %s is greater than stop date %s',
                $startDate->format('Y-m-d'),
                $stopDate->format('Y-m-d')
            )
        );
        $this->startDate = $startDate->setTime(0, 0, 0);
        $this->stopDate = $stopDate->setTime(0, 0, 0);
    }

    /**
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $stopDate
     * @return Duration
     */
    public static function between(
        DateTimeInterface $startDate,
        DateTimeInterface $stopDate
    ) {
        return new Duration($startDate, $stopDate);
    }

    /**
     * @param DateTimeInterface $aDate
     * @return Duration
     */
    public static function workWeekContaining(DateTimeInterface $aDate)
    {
        return new Duration(
            clone $aDate->modify('monday this week'),
            $aDate->modify('friday this week')
        );
    }

    /**
     * @param DateTimeInterface $aDate
     * @return bool
     */
    public function contains(DateTimeInterface $aDate)
    {
        return $aDate >= $this->startDate && $aDate <= $this->stopDate;
    }

    /**
     * @return DateTimeInterface
     */
    public function startDate()
    {
        return $this->startDate;
    }

    /**
     * @return DateTimeInterface
     */
    public function stopDate()
    {
        return $this->stopDate;
    }
}
