<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\StudentHasCheckedIn;
use Codeup\Bootcamps\StudentId;
use Codeup\DataBuilders\A as An;
use Codeup\DomainEvents\EventPublisher;
use Codeup\DomainEvents\RecordsEvents;
use DateTime;

class AttendanceGenerator
{
    use RecordsEvents;

    /** @var Attendances */
    private $attendances;

    /** @var EventPublisher */
    private $publisher;

    /**
     * @param Attendances $attendances
     * @param EventPublisher $publisher
     */
    public function __construct(
        Attendances $attendances,
        EventPublisher $publisher
    ) {
        $this->attendances = $attendances;
        $this->publisher = $publisher;
    }

    /**
     * @param array $absentStudents
     * @param DateTime $aDate
     * @return array The information of the randomly selected students
     */
    public function generateRandomAttendance(
        array $absentStudents,
        DateTime $aDate
    ) {
        $this->generateAttendances(
            $randomStudents = $this->randomSample($absentStudents),
            $aDate
        );
        $this->publisher->publish($this->events());

        return $randomStudents;
    }

    /**
     * @param array $absentStudents
     * @return array
     */
    protected function randomSample(array $absentStudents)
    {
        $studentsInformation = [];
        foreach ($absentStudents as $bootcampId => $students) {
            shuffle($students);
            $studentsInformation[$bootcampId] = array_slice(
                $students,
                0,
                mt_rand(1, 5)
            );
        }
        return $studentsInformation;
    }

    /**
     * @param array $students
     * @param DateTime $aDate
     */
    protected function generateAttendances(
        array $students,
        DateTime $aDate
    ) {
        foreach ($students as $studentsInBootcamp) {
            $this->attendanceForBootcamp($studentsInBootcamp, $aDate);
        }
    }

    /**
     * @param array $students
     * @param DateTime $today
     */
    protected function attendanceForBootcamp(array $students, DateTime $today)
    {
        foreach ($students as $student) {
            $checkInTime = $this->checkInTime($today, $student['start_time']);
            $attendance = An::attendance()
                ->withId($this->attendances->nextAttendanceId()->value())
                ->withStudentId(StudentId::fromLiteral($student['student_id']))
                ->recordedAt($checkInTime)
                ->build();
            $this->attendances->add($attendance);
            $this->recordThat(new StudentHasCheckedIn($attendance->id()));
        }
    }

    /**
     * Generate a random check-in time for today, close to the bootcamp's start
     * time (between 90 and -90 minutes)
     *
     * @param DateTime $today
     * @param $bootcampStartTime
     * @return DateTime
     */
    protected function checkInTime(DateTime $today, $bootcampStartTime)
    {
        $startTime = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $bootcampStartTime
        );

        $checkInTime = clone $today;
        $checkInTime->setTime(
            (int)$startTime->format('H'),
            (int)$startTime->format('i'),
            (int)$startTime->format('s')
        );
        $minutes = mt_rand(-90, 90);
        $checkInTime->modify("$minutes minutes");

        return $checkInTime;
    }
}
