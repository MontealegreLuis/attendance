<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\Bootcamps\Students;
use Doctrine\DBAL\Connection;
use League\Csv\Reader;
use PDO;

class RegisterStudentsInformation
{
    /** @var Connection */
    private $connection;

    /** @var Students */
    private $students;

    /**
     * @param Connection $connection
     * @param Students $students
     */
    public function __construct(Connection $connection, Students $students)
    {
        $this->connection = $connection;
        $this->students = $students;
    }

    public function register($path, $bootcampId)
    {
        $csv = Reader::createFromPath($path);
        $statement = $this->connection->prepare(
            "INSERT INTO students (student_id, name, mac_address, bootcamp_id)
             VALUES (:student_id, :name, :mac_address, :bootcamp_id)"
        );

        $csv->each(function ($student) use (&$statement, $bootcampId) {
            $statement->bindValue(
                ':student_id',
                $this->students->nextStudentId()->value(),
                PDO::PARAM_STR
            );
            $statement->bindValue(':name', $student[0], PDO::PARAM_STR);
            $statement->bindValue(':mac_address', $student[1], PDO::PARAM_STR);
            $statement->bindValue(':bootcamp_id', $bootcampId, PDO::PARAM_STR);

            return $statement->execute();
        });
    }
}
