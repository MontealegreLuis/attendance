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
        $this->updateNextIdentityValue();

        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('next_val')
            ->from('attendances_seq')
            ->setMaxResults(1)
        ;

        return AttendanceId::fromLiteral($builder->execute()->fetchColumn());
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

    private function updateNextIdentityValue()
    {
        $platform = $this->connection->getDatabasePlatform()->getName();
        if ($platform === 'mysql') {
            return $this->connection->executeQuery(
                'UPDATE attendances_seq SET next_val = LAST_INSERT_ID(next_val + 1)'
            );
        } elseif ($platform === 'sqlite') {
            return $this->connection->executeQuery(
                'UPDATE attendances_seq SET next_val = next_val + 1'
            );
        }
        throw InvalidPlatform::named($platform);
    }
}
