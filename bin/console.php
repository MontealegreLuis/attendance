<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Codeup\Attendance\DoRollCall;
use Codeup\Console\Command\RollCallCommand;
use Codeup\Dbal\AttendancesRepository;
use Codeup\Dbal\StudentsRepository;
use Codeup\Goutte\GoutteAttendanceChecker;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Application;

$options = require __DIR__ . '/../config.php';
$connection = DriverManager::getConnection($options['dbal']);
$application = new Application();
$application->add(new RollCallCommand( new DoRollCall(
    new GoutteAttendanceChecker($options['dhcp']['page']),
    new StudentsRepository($connection),
    new AttendancesRepository($connection)
)));
$application->run();
