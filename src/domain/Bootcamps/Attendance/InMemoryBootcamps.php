<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps\Attendance;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Bootcamps;
use DateTime;
use SplObjectStorage;

class InMemoryBootcamps implements Bootcamps
{
    private static $nextBootcampId = 0;

    /** @var SplObjectStorage */
    private $bootcamps;

    public function __construct()
    {
        $this->bootcamps = new SplObjectStorage();
    }

    /**
     * @param Bootcamp $bootcamp
     */
    public function add(Bootcamp $bootcamp)
    {
        $this->bootcamps->attach($bootcamp);
    }

    /**
     * @return BootcampId
     */
    public function nextBootcampId()
    {
        self::$nextBootcampId++;

        return BootcampId::fromLiteral(self::$nextBootcampId);
    }

    /**
     * @param BootcampId $bootcampId
     * @return Bootcamp
     */
    public function with(BootcampId $bootcampId)
    {
        /** @var Bootcamp $bootcamp */
        foreach ($this->bootcamps as $bootcamp) {
            if ($bootcamp->id()->equals($bootcampId)) {
                return $bootcamp;
            }
        }
    }

    /**
     * @param DateTime $onDate
     * @return array
     */
    public function attendance(DateTime $onDate)
    {
        return [];
    }

    /**
     * @param DateTime $onDate
     * @return array
     */
    public function onTime(DateTime $onDate)
    {
        return [];
    }

    public function daysWithPerfectAttendance()
    {
        return [];
    }
}
