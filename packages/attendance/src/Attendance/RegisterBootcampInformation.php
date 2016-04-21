<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\Bootcamps\Duration;
use Codeup\Bootcamps\Schedule;
use DateTime;

class RegisterBootcampInformation
{
    /** @var Duration */
    private $duration;

    /** @var string */
    private $cohortName;

    /** @var Schedule */
    private $schedule;

    /**
     * @param array $validInput
     */
    private function __construct(array $validInput)
    {
        $this->duration = Duration::between(
            DateTime::createFromFormat('Y-m-d', $validInput['start_date']),
            DateTime::createFromFormat('Y-m-d', $validInput['stop_date'])
        );
        $this->cohortName = $validInput['cohort_name'];
        $this->schedule = Schedule::withClassTimeBetween(
            DateTime::createFromFormat('H:i', $validInput['start_time']),
            DateTime::createFromFormat('H:i', $validInput['stop_time'])
        );
    }

    /**
     * @param array $validInput
     * @return RegisterBootcampInformation
     */
    public static function from(array $validInput)
    {
        return new self($validInput);
    }

    /**
     * @return Duration
     */
    public function duration()
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function cohortName()
    {
        return $this->cohortName;
    }

    /**
     * @return Schedule
     */
    public function schedule()
    {
        return $this->schedule;
    }
}
