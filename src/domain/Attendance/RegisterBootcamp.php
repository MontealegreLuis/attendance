<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\Bootcamps;

class RegisterBootcamp
{
    /** @var Bootcamps */
    private $bootcamps;

    /**
     * @param Bootcamps $bootcamps
     */
    public function __construct(Bootcamps $bootcamps)
    {
        $this->bootcamps = $bootcamps;
    }

    /**
     * @param RegisterBootcampInformation $information
     */
    public function register(RegisterBootcampInformation $information)
    {
        $bootcamp = Bootcamp::start(
            $this->bootcamps->nextBootcampId(),
            $information->duration(),
            $information->cohortName(),
            $information->schedule()
        );
        $this->bootcamps->add($bootcamp);
    }
}
