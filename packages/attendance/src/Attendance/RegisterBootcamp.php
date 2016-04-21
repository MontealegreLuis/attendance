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

    /** @var RegisterStudentsInformation */
    private $students;

    /**
     * @param Bootcamps $bootcamps
     * @param RegisterStudentsInformation $students
     */
    public function __construct(
        Bootcamps $bootcamps,
        RegisterStudentsInformation $students
    ) {
        $this->bootcamps = $bootcamps;
        $this->students = $students;
    }

    /**
     * @param RegisterBootcampInformation $information
     * @param string $path
     */
    public function register(RegisterBootcampInformation $information, $path)
    {
        $bootcamp = Bootcamp::start(
            $this->bootcamps->nextBootcampId(),
            $information->duration(),
            $information->cohortName(),
            $information->schedule()
        );
        $this->bootcamps->add($bootcamp);
        $this->students->register($path, $bootcamp->id()->value());
    }
}
