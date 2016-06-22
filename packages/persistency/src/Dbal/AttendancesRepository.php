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

    public function update(Attendance $attendance)
    {
        $information = $attendance->information();
        $this->connection->update('attendances',[
            'date' => $information->onDate()->format('Y-m-d H:i:s'),
        ], [
            'attendance_id' => $information->id()->value(),
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
     * Calculates the total of students, the count of students already in
     * class, and how many of they arrived on time, for the bootcamp associated
     * with this attendance
     *
     * @param AttendanceId $attendanceId
     * @return array
     */
    public function summary(AttendanceId $attendanceId)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->addSelect('b.bootcamp_id')
            ->addSelect('COUNT(a.attendance_id) AS students_count')
            ->addSelect('COUNT(
                CASE WHEN TIME(a.date) <= TIME(b.start_time)
                THEN 1
                ELSE NULL
                END
            ) AS on_time_students_count')
            ->from('bootcamps', 'b')
            ->innerJoin(
                'b',
                'students',
                's',
                'b.bootcamp_id = s.bootcamp_id'
            )
            ->innerJoin(
                's',
                'attendances',
                'a',
                'a.student_id = s.student_id AND DATE(a.date) = (
                    SELECT DATE(a.date)
                    FROM attendances a
                    WHERE a.attendance_id = :attendanceId
                )'
            )
            ->groupBy('b.bootcamp_id')
            ->having('b.bootcamp_id = (
                SELECT b.bootcamp_id
                FROM bootcamps b
                INNER JOIN students s ON s.bootcamp_id = b.bootcamp_id
                INNER JOIN attendances a ON a.student_id = s.student_id
                WHERE  a.attendance_id = :attendanceId
            )')
            ->setParameter('attendanceId', $attendanceId->value())
        ;
        return $builder->execute()->fetch();
    }
}
