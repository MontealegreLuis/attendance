<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Codeup\Console\AttendanceApplication;
use Codeup\Pimple\ConsoleServiceProvider;
use Dotenv\Dotenv;
use Pimple\Container;

$env = new Dotenv(__DIR__ . '/../');
$env->load();
$env->required([
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST',
    'MYSQL_DB',
    'DHCP_LOGIN_PAGE',
    'DHCP_STATUS_PAGE',
    'DHCP_USER',
    'DHCP_PASSWORD',
]);

$provider = new ConsoleServiceProvider(require __DIR__ . '/../config.php');
$provider->register($container = new Container());
$application = new AttendanceApplication($container);
$application->run();
