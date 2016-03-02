<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Codeup\Pimple\AttendanceServiceProvider;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App();
$provider = new AttendanceServiceProvider(
    require __DIR__ . '/../config.php'
);
$provider->register($app->getContainer());

$app->get('/', function ($request, $response) {
    return $response->write($this->view->fetch('attendance.html.twig'));
});

$app->run();
