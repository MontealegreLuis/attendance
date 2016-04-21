<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\Dbal\Queries\AbsentStudents;
use Codeup\Dbal\Queries\OngoingBootcamps;
use DateTime;
use Doctrine\DBAL\Connection;

class AttendanceAnalyzer
{
    /** @var Connection */
    private $connection;

    /**
     * @param Connection $connection
     * @param Attendances $attendances
     * @param EventPublisher $publisher
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param DateTime $onADate
     * @return array
     */
    public function ongoingBootcamps(DateTime $onADate)
    {
        $ongoingBootcamps = new OngoingBootcamps($this->connection);

        return $ongoingBootcamps->during($onADate);
    }

    /**
     * @param array $bootcamps
     * @param DateTime $aDate
     * @return array
     */
    public function absentStudentsIn(array $bootcamps, DateTime $aDate)
    {
        $absentStudents = new AbsentStudents($this->connection);

        return $absentStudents->enrolledDuring($bootcamps, $aDate);
    }
}
