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
use SplObjectStorage;

class InMemoryBootcamps implements Bootcamps
{
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
}
