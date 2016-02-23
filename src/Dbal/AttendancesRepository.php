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

    public function add(Attendance $attendance)
    {
        $information = $attendance->information();
        $this->connection->insert('attendances', [
            'attendance_id' => $information->id()->value(),
            'type' => $information->type(),
            'date' => $information->onDate()->format('Y-m-d H:i:s'),
            'student_id' => $information->student()->id()->value(),
        ]);
    }
}
