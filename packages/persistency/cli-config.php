<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\HelperSet;

$options = require __DIR__ . '/config.tests.php';

$connection = DriverManager::getConnection($options['dbal']);

return new HelperSet([
    'db' => new ConnectionHelper($connection),
    'dialog' => new QuestionHelper(),
]);
