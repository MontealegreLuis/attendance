<?php
use Codeup\Pimple\AttendanceServiceProvider;
use Pimple\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

require __DIR__ . '/../vendor/autoload.php';

(new AttendanceServiceProvider(require __DIR__ . '/../config.dist.php'))
    ->register($container = new Container())
;

/** @var \Codeup\Attendance\UpdateAttendanceList $useCase */
$useCase = $container['attendance.update_attendance'];

$response = new StreamedResponse(function () use ($useCase) {
    $useCase->updateList();
    sleep(3);
});

$response->prepare(Request::createFromGlobals());
$response->headers->set('Content-Type', 'text/event-stream');
$response->send();
