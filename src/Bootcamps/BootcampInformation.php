<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;

class BootcampInformation
{
    /** @var string */
    private $cohortName;

    /** @var DateTime */
    private $startDate;

    /** @var DateTime */
    private $stopDate;

    /** @var DateTime */
    private $startTime;

    /** @var DateTime */
    private $stopTime;

    public function __construct(
        $cohortName,
        DateTime $startDate,
        DateTime $stopDate,
        DateTime $startTime,
        DateTime $stopTime
    ) {
        $this->cohortName;
        $this->startDate;
        $this->stopDate;
        $this->startTime;
        $this->stopTime;
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

    /**
     * @return string
     */
    public function cohortName()
    {
        return $this->cohortName;
    }
}
