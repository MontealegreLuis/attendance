<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\Bootcamps\Bootcamp;
use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Bootcamps;
use DateTime;
use Doctrine\DBAL\Connection;

class BootcampsRepository implements Bootcamps
{
    private $connection;

    /**
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Bootcamp $bootcamp)
    {
        $information = $bootcamp->information();
        $this->connection->insert('bootcamps', [
            'bootcamp_id' => $information->id()->value(),
            'cohort_name' => $information->cohortName(),
            'start_date' => $information->startDate()->format('Y-m-d'),
            'stop_date' => $information->stopDate()->format('Y-m-d'),
            'start_time' => $information->startTime()->format('Y-m-d H:m:i'),
            'stop_time' => $information->stopTime()->format('Y-m-d H:m:i'),
        ]);
    }

    /**
     * @param BootcampId $bootcampId
     * @return Bootcamp
     */
    public function with(BootcampId $bootcampId)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from('bootcamps', 'b')
            ->where('bootcamp_id = :bootcampId')
            ->setParameter('bootcampId', $bootcampId->value())
            ->setMaxResults(1)
        ;

        return Bootcamp::from($builder->execute()->fetch());
    }

    /**
     * @param DateTime $onDate
     * @return array
     */
    public function attendance(DateTime $onDate)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->addSelect('b.cohort_name')
            ->addSelect('COUNT(a.attendance_id) * 100.0 /
                (
                SELECT COUNT(*)
                FROM students
                WHERE students.bootcamp_id = b.bootcamp_id
                ) AS attendance_percentage
            ')
            ->from('bootcamps', 'b')
            ->innerJoin('b', 'students', 's', 'b.bootcamp_id = s.bootcamp_id')
            ->innerJoin(
                's', 'attendances', 'a',
                's.student_id = a.student_id AND DATE(a.date) = :date'
            )
            ->groupBy('b.bootcamp_id')
            ->setParameter('date', $onDate->format('Y-m-d'));

        return $builder->execute()->fetchAll();
    }

    /**
     * @param DateTime $onDate
     * @return array
     */
    public function onTime(DateTime $onDate)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->addSelect('b.cohort_name')
            ->addSelect('COUNT(a.attendance_id) * 100.0 /
                (
                SELECT COUNT(*)
                FROM students
                WHERE students.bootcamp_id = b.bootcamp_id
                ) AS on_time_attendance
            ')
            ->from('bootcamps', 'b')
            ->innerJoin('b', 'students', 's', 'b.bootcamp_id = s.bootcamp_id')
            ->leftJoin(
                's', 'attendances', 'a',
                's.student_id = a.student_id AND DATE(a.date) = :date AND TIME(a.date) <= TIME(b.start_time)'
            )
            ->groupBy('b.bootcamp_id')
            ->setParameter('date', $onDate->format('Y-m-d'));

        return $builder->execute()->fetchAll();
    }
}
