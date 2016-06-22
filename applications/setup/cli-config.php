<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Dotenv\Dotenv;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\HelperSet;

$env = new Dotenv(__DIR__);
$env->load();
$env->required([
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST',
    'MYSQL_DB',
]);
$options = require __DIR__ . '/config.php';

$connection = DriverManager::getConnection($options['dbal']);

return new HelperSet([
    'db' => new ConnectionHelper($connection),
    'dialog' => new QuestionHelper(),
]);
