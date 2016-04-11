<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Attendance\UpdateAttendanceList;
use Codeup\EventSource\EventSourceStream;
use Codeup\JmsSerializer\JsonSerializer;
use Codeup\Messaging\MessagePublisher;
use Codeup\Slim\HomeController;
use Codeup\Slim\RegisterBootcampController;
use Codeup\Slim\RegisterStudentsController;
use Codeup\Slim\UpdateAttendanceController;
use Codeup\Twig\Extensions\AttendanceExtension;
use Igorw\EventSource\Stream;
use Pimple\Container;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class WebServiceProvider extends AttendanceServiceProvider
{
    public function register(Container $container)
    {
        parent::register($container);
        $container['view'] = function () use ($container) {
            $view = new Twig($this->options['twig']['templates'], [
                'cache' => $this->options['twig']['cache'],
                'debug' => $this->options['twig']['debug'],
            ]);
            $view->addExtension(new TwigExtension(
                $container['router'],
                $container['request']->getUri()
            ));
            $view->addExtension(new AttendanceExtension());

            return $view;
        };
        $container['HomeController'] = function () use ($container) {
            return new HomeController(
                $container['attendance.bootcamps'],
                $container['view']
            );
        };
        $container['RegisterBootcampController'] = function () use ($container) {
            return new RegisterBootcampController(
                $container['view'],
                $container['attendance.bootcamps']
            );
        };
        $container['RegisterStudentsController'] = function () use ($container) {
            return new RegisterStudentsController(
                $container['view'],
                $container['attendance.bootcamps'],
                $container['attendance.students'],
                $container['db.connection']
            );
        };
        $container['UpdateAttendanceController'] = function () use ($container) {
            return new UpdateAttendanceController(
                new MessagePublisher(
                    $container['messages.tracker'],
                    $container['events.store']
                ),
                new UpdateAttendanceList(
                    new EventSourceStream(new Stream()),
                    $container['attendance.attendances'],
                    new JsonSerializer()
                )
            );
        };
    }
}
