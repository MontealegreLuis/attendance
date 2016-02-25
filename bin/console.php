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
use Codeup\Dbal\EventStoreRepository;
use Codeup\DomainEvents\EventPublisher;
use Codeup\DomainEvents\PersistEventSubscriber;
use Codeup\JmsSerializer\JsonSerializer;
use Codeup\WebDriver\WebDriverAttendanceChecker;
use Doctrine\DBAL\DriverManager;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Symfony\Component\Console\Application;

$options = require __DIR__ . '/../config.php';
$connection = DriverManager::getConnection($options['dbal']);
$application = new Application();
$publisher = new EventPublisher();
$publisher->subscribe(new PersistEventSubscriber(
    new EventStoreRepository($connection, new JsonSerializer())
));
$useCase = new DoRollCall(
    new WebDriverAttendanceChecker(
        RemoteWebDriver::create(
            $options['webdriver']['host'],
            $options['webdriver']['capabilities'],
            $options['webdriver']['timeout']
        ),
        $options['dhcp']
    ),
    new StudentsRepository($connection),
    new AttendancesRepository($connection)
);
$useCase->setPublisher($publisher);
$application->add(new RollCallCommand($useCase));
$application->run();
