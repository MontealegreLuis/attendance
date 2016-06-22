<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Codeup\Console\SetupApplication;
use Codeup\Pimple\SetupServiceProvider;
use Dotenv\Dotenv;
use Pimple\Container;

$env = new Dotenv(__DIR__ . '/../');
$env->load();
$env->required([
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST',
    'MYSQL_DB',
]);
$provider = new SetupServiceProvider(require __DIR__ . '/../config.php');
$helperSet = require __DIR__ . '/../cli-config.php';
$provider->register($container = new Container());
$application = new SetupApplication($container, $helperSet);
$application->run();
