<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use Bootcamps\BootcampInformation;
use DateTime;

class Bootcamp
{
    /** @var BootcampId */
    private $bootcampId;

    /** @var Duration */
    private $duration;

    /** @var string */
    private $cohortName;

    /** @var Schedule */
    private $schedule;

    /**
     * @param BootcampId $bootcampId
     * @param Duration $duration
     * @param string $cohortName
     * @param Schedule $schedule
     */
    private function __construct(
        BootcampId $bootcampId,
        Duration $duration,
        $cohortName,
        Schedule $schedule
    ) {
        $this->setCohortName($cohortName);
        $this->duration = $duration;
        $this->schedule = $schedule;
        $this->bootcampId = $bootcampId;
    }

    /**
     * @param BootcampId $bootcampId
     * @param Duration $duration
     * @param string $cohortName
     * @param Schedule $schedule
     * @return Bootcamp
     */
    public static function start(
        BootcampId $bootcampId,
        Duration $duration,
        $cohortName,
        Schedule $schedule
    ) {
        return new Bootcamp($bootcampId, $duration, $cohortName, $schedule);
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
     * @return BootcampInformation
     */
    public function information()
    {
        return new BootcampInformation(
            $this->cohortName,
            $this->duration->startDate(),
            $this->duration->stopDate(),
            $this->schedule->startTime(),
            $this->schedule->stopTime()
        );
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
