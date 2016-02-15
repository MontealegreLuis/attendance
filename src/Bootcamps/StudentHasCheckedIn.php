<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

use DateTime;
use Codeup\DomainEvents\Event;

class StudentHasCheckedIn implements Event
{
    /** @var DateTime */
    private $occurredOn;

    /** @var StudentId */
    private $studentId;

    /**
     * @param StudentId $studentId
     * @param DateTime $when
     */
    public function __construct(StudentId $studentId, DateTime $when)
    {
        $this->studentId = $studentId;
        $this->occurredOn = $when;
    }

    /**
     * @return StudentId
     */
    public function studentId()
    {
        return $this->studentId;
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
