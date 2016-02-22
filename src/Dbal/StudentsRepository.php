<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\Bootcamps\Attendance;
use Codeup\Bootcamps\MacAddress;
use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\Students;
use DateTime;
use Doctrine\DBAL\Connection;
use PDO;

class StudentsRepository implements Students
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
     * @param DateTime $today
     * @param MacAddress[] $addresses
     * @return Student[]
     */
    public function attending(DateTime $today, array $addresses)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from('students', 's')
            ->innerJoin('s', 'bootcamps', 'b', 's.bootcamp_id = b.bootcamp_id')
            ->leftJoin(
                's',
                'attendances',
                'a',
                'a.student_id = s.student_id AND a.date = :today AND a.type = :type'
            )
            ->where('b.start_date <= :today AND b.stop_date >= :today')
            ->andWhere("s.mac_address IN ({$this->buildParameters($addresses)})")
            ->setParameter('today', $today->format('Y-m-d'))
            ->setParameter('type', Attendance::CHECK_IN)
        ;

        $students = $builder->execute()->fetchAll();
        //Not students instances

        return $students;
    }

    /**
     * @param array $addresses
     * @return string
     */
    private function buildParameters(array $addresses)
    {
        $values = [];

        /** @var MacAddress $address */
        foreach ($addresses as $address) {
            $values[] = $this->connection->quote(
                $address->value(),
                PDO::PARAM_STR
            );
        }

        return implode(', ', $values);
    }

    public function add(Student $student)
    {
        $information = $student->information();
        $this->connection->insert('students', [
            'student_id' => $information->id()->value(),
            'name' => $information->name(),
            'mac_address' => $information->macAddress()->value(),
            'bootcamp_id' => $information->bootcamp()->id()->value(),
        ]);
    }

    public function update(Student $student)
    {
        $information = $student->information();
        $this->connection->update('students', [
            'student_id' => $information->id()->value(),
            'name' => $information->name(),
            'mac_address' => $information->macAddress()->value(),
            'bootcamp_id' => $information->bootcamp()->id()->value(),
        ], [
            'student_id' => $information->id()->value()
        ]);

        // insert attendance record
    }
}
