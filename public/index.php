<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Codeup\Pimple\AttendanceServiceProvider;
use Igorw\EventSource\Stream;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App();
(new AttendanceServiceProvider(require __DIR__ . '/../config.php'))
    ->register($app->getContainer())
;

$app->get('/', function ($_, $response) {
    return $response->write($this->view->fetch('attendance.html.twig'));
});

$app->run();
