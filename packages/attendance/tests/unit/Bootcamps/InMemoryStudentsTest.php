<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Codeup\Bootcamps\Attendance;

use Codeup\ContractTests\StudentsTest;

class InMemoryStudentsTest extends StudentsTest
{
    public function studentsInstance()
    {
        return new InMemoryStudents();
    }

    public function bootcampsInstance()
    {
        return new InMemoryBootcamps();
    }
}
