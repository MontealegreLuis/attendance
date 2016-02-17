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
    /** @var BootcampId */
    private $bootcampId;

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

    /**
     * @param BootcampId $bootcampId
     * @param $cohortName
     * @param DateTime $startDate
     * @param DateTime $stopDate
     * @param DateTime $startTime
     * @param DateTime $stopTime
     */
    public function __construct(
        BootcampId $bootcampId,
        $cohortName,
        DateTime $startDate,
        DateTime $stopDate,
        DateTime $startTime,
        DateTime $stopTime
    ) {
        $this->bootcampId = $bootcampId;
        $this->cohortName = $cohortName;
        $this->startDate = $startDate;
        $this->stopDate = $stopDate;
        $this->startTime = $startTime;
        $this->stopTime = $stopTime;
    }

    /**
     * @return BootcampId
     */
    public function id()
    {
        return $this->bootcampId;
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
