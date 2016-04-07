<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

interface Bootcamps
{
    /** @return BootcampId */
    public function nextBootcampId();

    /**
     * @param Bootcamp $bootcamp
     */
    public function add(Bootcamp $bootcamp);

    /**
     * @param BootcampId $bootcampId
     * @return Bootcamp
     */
    public function with(BootcampId $bootcampId);

    /**
     * @param DateTime $onDate
     * @return array
     */
    public function attendance(DateTime $onDate);

    /**
     * @param DateTime $onDate
     * @return array
     */
    public function onTime(DateTime $onDate);

    /**
     * @return array
     */
    public function daysWithPerfectAttendance();
}
