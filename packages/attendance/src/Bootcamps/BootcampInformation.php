<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTimeInterface;

class BootcampInformation
{
    /** @var BootcampId */
    private $bootcampId;

    /** @var string */
    private $cohortName;

    /** @var DateTimeInterface */
    private $startDate;

    /** @var DateTimeInterface */
    private $stopDate;

    /** @var DateTimeInterface */
    private $startTime;

    /** @var DateTimeInterface */
    private $stopTime;

    /**
     * @param BootcampId $bootcampId
     * @param $cohortName
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $stopDate
     * @param DateTimeInterface $startTime
     * @param DateTimeInterface $stopTime
     */
    public function __construct(
        BootcampId $bootcampId,
        $cohortName,
        DateTimeInterface $startDate,
        DateTimeInterface $stopDate,
        DateTimeInterface $startTime,
        DateTimeInterface $stopTime
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

    /**
     * @return DateTimeInterface
     */
    public function startTime()
    {
        return $this->startTime;
    }

    /**
     * @return DateTimeInterface
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
