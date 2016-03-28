<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Codeup\Pimple\MessagingServiceProvider;
use Pimple\Container;

require __DIR__ . '/../vendor/autoload.php';

(new MessagingServiceProvider(require __DIR__ . '/../config.php'))
    ->register($container = new Container())
;

/** @var \Codeup\Symfony\UpdateAttendanceController $controller */
$controller = $container['controllers.update_attendance'];
$controller->updateListAction();
