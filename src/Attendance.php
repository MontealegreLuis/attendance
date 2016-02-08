<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup;

use DateTime;

class Attendance
{
    const CHECK_IN = 0;
    const CHECK_OUT = 1;

    /** @var DateTime */
    private $date;

    /** @var int */
    private $type;

    /**
     * @param DateTime $date
     * @param int $type
     */
    private function __construct(DateTime $date, $type)
    {
        $this->date = $date;
        $this->type = $type;
    }

    /**
     * @param DateTime $aDate
     * @return Attendance
     */
    public static function checkOut(DateTime $aDate)
    {
        return new Attendance($aDate, self::CHECK_OUT);
    }

    /**
     * @param DateTime $aDate
     * @return Attendance
     */
    public static function checkIn(DateTime $aDate)
    {
        return new Attendance($aDate, self::CHECK_IN);
    }

    /**
     * @return DateTime
     */
    public function onDate()
    {
        return $this->date;
    }
}
