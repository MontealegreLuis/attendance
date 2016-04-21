<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Codeup\Console\AttendanceApplication;
use Codeup\Pimple\ConsoleServiceProvider;
use Pimple\Container;

$provider = new ConsoleServiceProvider(require __DIR__ . '/../config.php');
$provider->register($container = new Container());
$application = new AttendanceApplication($container);
$application->run();