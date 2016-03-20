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
use Doctrine\DBAL\Connection;
use Underscore\Types\Arrays;

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

    public function attendance()
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from('bootcamps', 'b')
            ->innerJoin('b', 'students', 's', 'b.bootcamp_id = s.bootcamp_id')
            ->leftJoin('s', 'attendances', 'a', 's.student_id = a.student_id')
        ;

        return Arrays::group($builder->execute()->fetchAll(), 'bootcamp_id');
    }
}
