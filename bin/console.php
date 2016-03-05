<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Codeup\Console\AttendanceApplication;
use Codeup\Pimple\AttendanceServiceProvider;
use Pimple\Container;

$provider = new AttendanceServiceProvider(require __DIR__ . '/../config.dist.php');
$provider->register($container = new Container());
$application = new AttendanceApplication($container);
$application->run();
