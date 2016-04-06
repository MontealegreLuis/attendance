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
use Codeup\Symfony\UpdateAttendanceController;
use Igorw\EventSource\Stream;
use Pimple\Container;

class MessagingServiceProvider extends AttendanceServiceProvider
{
    public function register(Container $container)
    {
        parent::register($container);
        $container['messages.publisher'] = function () use ($container) {
            return new MessagePublisher(
                $container['messages.tracker'],
                $container['events.store']
            );
        };
        $container['attendance.update_attendance'] = function () use ($container) {
            return new UpdateAttendanceList(
                new EventSourceStream(new Stream()),
                $container['attendance.attendances'],
                new JsonSerializer()
            );
        };
        $container['controllers.update_attendance'] = function () use ($container) {
            return new UpdateAttendanceController(
                $container['messages.publisher'],
                $container['attendance.update_attendance']
            );
        };
    }
}
