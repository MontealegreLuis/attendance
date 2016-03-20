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

$app = new App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);
(new AttendanceServiceProvider(require __DIR__ . '/../config.php'))
    ->register($container = $app->getContainer())
;

$app->get('/', function ($_, $response) use ($container) {
    $attendances = $container['attendance.bootcamps']->attendance();
    return $response->write($this->view->fetch('attendance.html.twig', [
        'attendances' => array_shift($attendances),
    ]));
});

$app->run();
