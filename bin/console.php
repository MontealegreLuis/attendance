<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Codeup\Attendance\PerformRollCall;
use Codeup\Bootcamps\Attendance\InMemoryStudents;
use Codeup\Bootcamps\MacAddress;
use Codeup\Console\Command\RollCallCommand;
use Codeup\DataBuilders\A;
use Codeup\Goutte\GoutteAttendanceChecker;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new RollCallCommand( new PerformRollCall(
    new GoutteAttendanceChecker('http://localhost:8000/dhcp_status.html'),
    $students = new InMemoryStudents()
)));
$student = A::student()
    ->withMacAddress(MacAddress::withValue('e0:ac:cb:82:46:6e'))
    ->build()
;
$students->add($student);
$application->run();
