<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\Student;
use Doctrine\DBAL\Connection;

class AttendancesRepository implements Attendances
{
    use ProvidesIdentifiers;

    /** @var Connection */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return AttendanceId
     */
    public function nextAttendanceId()
    {
        return AttendanceId::fromLiteral(
            $this->nextIdentifierValue($this->connection, 'attendances_seq')
        );
    }

    /**
     * @param Attendance $attendance
     */
    public function add(Attendance $attendance)
    {
        $information = $attendance->information();
        $this->connection->insert('attendances', [
            'attendance_id' => $information->id()->value(),
            'type' => $information->type(),
            'date' => $information->onDate()->format('Y-m-d H:i:s'),
            'student_id' => $information->studentId()->value(),
        ]);
    }

    /**
     * @param AttendanceId $attendanceId
     * @return Student
     */
    public function with(AttendanceId $attendanceId)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from('attendances', 'a')
            ->where('a.attendance_id = :attendanceId')
            ->setMaxResults(1)
            ->setParameter('attendanceId', $attendanceId->value())
        ;
        $attendanceInformation = $builder->execute()->fetch();

        if ($attendanceInformation) {
            return Attendance::from($attendanceInformation);
        }
    }

    /**
     * @param AttendanceId $attendanceId
     * @return array
     */
    public function detailsOf(AttendanceId $attendanceId)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from('attendances', 'a')
            ->innerJoin('a', 'students', 's', 'a.student_id = s.student_id')
            ->innerJoin('s', 'bootcamps', 'b', 's.bootcamp_id = b.bootcamp_id')
            ->where('a.attendance_id = :attendanceId')
            ->setMaxResults(1)
            ->setParameter('attendanceId', $attendanceId->value())
        ;

        return $builder->execute()->fetch();
    }
}
