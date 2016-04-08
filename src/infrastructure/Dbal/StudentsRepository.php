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
use Codeup\Bootcamps\StudentId;
use Codeup\Bootcamps\Students;
use DateTime;
use Doctrine\DBAL\Connection;
use PDO;

class StudentsRepository implements Students
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

    public function nextStudentId()
    {
        return StudentId::fromLiteral(
            $this->nextIdentifierValue($this->connection, 'students_seq')
        );
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
            ->select('
                s.student_id,
                s.name,
                s.mac_address,
                b.bootcamp_id,
                b.cohort_name,
                b.start_date,
                b.stop_date,
                b.start_time,
                b.stop_time,
                a.attendance_id,
                a.date,
                a.type
            ')
            ->from('students', 's')
            ->innerJoin('s', 'bootcamps', 'b', 's.bootcamp_id = b.bootcamp_id')
            ->leftJoin(
                's',
                'attendances',
                'a',
                'a.student_id = s.student_id AND DATE(a.date) = :today AND a.type = :type'
            )
            ->where('b.start_date <= :today AND :today <= b.stop_date')
            ->andWhere("s.mac_address IN ({$this->buildParameters($addresses)})")
            ->setParameter('today', $today->format('Y-m-d'))
            ->setParameter('type', Attendance::CHECK_IN)
        ;

        return array_map(function (array $values) {
            return Student::from($values);
        }, $builder->execute()->fetchAll());
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

    /**
     * @param Student $student
     */
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

    /**
     * @param Student $student
     */
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
    }

    /**
     * @param StudentId $studentId
     * @return Student
     */
    public function with(StudentId $studentId)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from('students', 's')
            ->where('s.student_id = :studentId')
            ->setMaxResults(1)
            ->setParameter('studentId', $studentId->value())
        ;

        return Student::from($builder->execute()->fetch());
    }
}
