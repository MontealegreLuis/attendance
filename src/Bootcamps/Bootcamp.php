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
    /** @var Duration */
    private $duration;

    /** @var string */
    private $cohortName;

    /** @var Schedule */
    private $schedule;

    /**
     * @param Duration $duration
     * @param string $cohortName
     * @param Schedule $schedule
     */
    private function __construct(
        Duration $duration,
        $cohortName,
        Schedule $schedule
    ) {
        $this->setCohortName($cohortName);
        $this->duration = $duration;
        $this->schedule = $schedule;
    }

    /**
     * @param Duration $duration
     * @param string $cohortName
     * @param Schedule $schedule
     * @return Bootcamp
     */
    public static function start(
        Duration $duration,
        $cohortName,
        Schedule $schedule
    ) {
        return new Bootcamp($duration, $cohortName, $schedule);
    }

    /**
     * @param DateTime $aDate
     * @return bool
     */
    public function isInProgress(DateTime $aDate)
    {
        return $this->duration->contains($aDate);
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
