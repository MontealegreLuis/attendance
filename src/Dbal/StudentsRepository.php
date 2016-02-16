<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\Bootcamps\Student;
use Codeup\Bootcamps\Students;
use DateTime;
use Doctrine\DBAL\Connection;

class StudentsRepository implements Students
{
    private $connection;

    /**
     * StudentsRepository constructor.
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function attending(DateTime $today, array $addresses)
    {
        // TODO: Implement attending() method.
    }

    public function add(Student $student)
    {
        // TODO: Implement add() method.
    }

    public function update(Student $student)
    {
        // TODO: Implement update() method.
    }
}
