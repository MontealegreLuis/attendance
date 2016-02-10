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
        AssertValueIs::greaterThan($stopDate, $startDate);
        $this->startDate = $startDate;
        $this->stopDate = $stopDate;
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
     * @return bool
     */
    public function contains(DateTime $aDate)
    {
        return $aDate >= $this->startDate && $aDate <= $this->stopDate;
    }
}
