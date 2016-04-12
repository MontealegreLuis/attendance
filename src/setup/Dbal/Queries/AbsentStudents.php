<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal\Queries;

use Codeup\Bootcamps\Attendance;
use DateTime;
use Doctrine\DBAL\Query\QueryBuilder;

class AbsentStudents extends QueryBuilder
{
    /**
     * @param array $bootcamps Bootcamps IDs
     * @param DateTime $aDate
     * @return array
     */
    public function enrolledDuring(array $bootcamps, DateTime $aDate)
    {
        $this
            ->addSelect('b.bootcamp_id')
            ->addSelect('b.start_time')
            ->addSelect('b.cohort_name')
            ->addSelect('s.student_id')
            ->addSelect('a.attendance_id')
            ->from('bootcamps', 'b')
            ->innerJoin('b', 'students', 's', 'b.bootcamp_id = s.bootcamp_id')
            ->leftJoin(
                's',
                'attendances',
                'a',
                's.student_id = a.student_id AND DATE(a.date) = :aDate AND a.type = :check_in'
            )
            ->where('b.bootcamp_id IN (' . implode(', ', $bootcamps) . ')')
            ->andWhere('a.attendance_id IS null')
            ->setParameter(':aDate', $aDate->format('Y-m-d'))
            ->setParameter(':check_in', Attendance::CHECK_IN)
        ;

        return $this->execute()->fetchAll();
    }
}
