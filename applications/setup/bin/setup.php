<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Codeup\Console\SetupApplication;
use Codeup\Pimple\SetupServiceProvider;
use Pimple\Container;

$provider = new SetupServiceProvider(require __DIR__ . '/../config.php');
$helperSet = require __DIR__ . '/../cli-config.php';
$provider->register($container = new Container());
$application = new SetupApplication($container, $helperSet);
$application->run();
