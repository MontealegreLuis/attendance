<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require getcwd() . '/vendor/autoload.php';

use Codeup\Console\DatabaseApplication;
use Codeup\Pimple\DatabaseServiceProvider;
use Pimple\Container;

$provider = new DatabaseServiceProvider(require getcwd() . '/config.php');
$helperSet = require getcwd() . '/cli-config.php';
$provider->register($container = new Container());
$application = new DatabaseApplication($container, $helperSet);
$application->run();
