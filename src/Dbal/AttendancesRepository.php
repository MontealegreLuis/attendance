<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Dbal;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\AttendanceId;
use Codeup\Bootcamps\Attendances;

class AttendancesRepository implements Attendances
{
    /**
     * @return AttendanceId
     */
    public function nextAttendanceId()
    {
        // TODO: Implement nextAttendanceId() method.
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
