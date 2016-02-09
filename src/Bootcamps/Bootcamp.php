<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

class Bootcamp
{
    /** @var DateTime */
    private $startDate;

    /** @var string */
    private $cohortName;

    /** @var BootcampSchedule */
    private $schedule;

    /**
     * @param DateTime $startDate
     * @param string $cohortName
     * @param BootcampSchedule $schedule
     */
    private function __construct(
        DateTime $startDate,
        $cohortName,
        BootcampSchedule $schedule
    ) {
        $this->setCohortName($cohortName);
        $this->startDate = $startDate;
        $this->schedule = $schedule;
    }

    /**
     * @param DateTime $onDate
     * @param string $cohortName
     * @param BootcampSchedule $schedule
     * @return Bootcamp
     */
    public static function start(
        Datetime $onDate,
        $cohortName,
        BootcampSchedule $schedule
    ) {
        return new Bootcamp($onDate, $cohortName, $schedule);
    }

    /**
     * @return string
     */
    public function cohortName()
    {
        return $this->cohortName;
    }

    /**
     * @param string $name
     */
    private function setCohortName($name)
    {
        AssertValueIs::notBlank(trim($name), "Cohort's name cannot be empty");
        $this->cohortName = $name;
    }
}
