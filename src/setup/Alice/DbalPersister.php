<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Alice;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Bootcamps;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\StudentHasCheckedIn;
use Codeup\Bootcamps\StudentId;
use Codeup\Bootcamps\Students;
use Codeup\DomainEvents\EventStore;
use Nelmio\Alice\PersisterInterface;

class DbalPersister implements PersisterInterface
{
    /** @var Bootcamps */
    private $bootcamps;

    /** @var Students */
    private $students;

    /** @var Attendances */
    private $attendances;

    /** @var EventStore */
    private $store;

    /**
     * @param Bootcamps $bootcamps
     * @param Students $students
     * @param Attendances $attendances
     * @param EventStore $store
     */
    public function __construct(
        Bootcamps $bootcamps,
        Students $students,
        Attendances $attendances,
        EventStore $store
    ) {
        $this->bootcamps = $bootcamps;
        $this->students = $students;
        $this->attendances = $attendances;
        $this->store = $store;
    }

    public function persist(array $objects)
    {
        foreach ($objects as $object) {
            $this->add($object);
        }
    }

    public function find($class, $id)
    {
        return $this->with($class, $id);
    }

    /**
     * @param mixed $object
     */
    private function add($object)
    {
        switch (get_class($object)) {
            case Bootcamp::class:
                $this->bootcamps->add($object);
                break;
            case Student::class:
                $this->students->add($object);
                break;
            case Attendance::class:
                $this->attendances->add($object);
                break;
            case StudentHasCheckedIn::class:
                $this->store->append($object);
        }
    }

    /**
     * @param string $class
     * @param int $id
     * @return Bootcamp
     */
    private function with($class, $id)
    {
        switch ($class) {
            case Bootcamp::class:
                return $this->bootcamps->with(BootcampId::fromLiteral($id));
            case Student::class:
                return $this->students->with(StudentId::fromLiteral($id));
            case Attendance::class:
                return $this->attendances->with(AttendanceId::fromLiteral($id));
        }
    }
}
