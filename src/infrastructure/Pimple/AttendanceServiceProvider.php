<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Attendance\UpdateAttendanceList;
use Codeup\Dbal\AttendancesRepository;
use Codeup\Dbal\BootcampsRepository;
use Codeup\Dbal\EventStoreRepository;
use Codeup\Dbal\MessageTrackerRepository;
use Codeup\Dbal\StudentsRepository;
use Codeup\JmsSerializer\JsonSerializer;
use Codeup\Messaging\MessagePublisher;
use Doctrine\DBAL\DriverManager;
use Igorw\EventSource\Stream;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

class AttendanceServiceProvider implements ServiceProviderInterface
{
    /** @var array */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['db.connection'] = function () {
            return DriverManager::getConnection($this->options['dbal']);
        };
        $container['events.store'] = function () use ($container) {
            return new EventStoreRepository(
                $container['db.connection'],
                new JsonSerializer()
            );
        };
        $container['messages.publisher'] = function () use ($container) {
            return new MessagePublisher(
                new MessageTrackerRepository($container['db.connection']),
                $container['events.store']
            );
        };
        $container['attendance.attendances'] = function () use ($container) {
            return new AttendancesRepository($container['db.connection']);
        };
        $container['attendance.bootcamps'] = function () use ($container) {
            return new BootcampsRepository($container['db.connection']);
        };
        $container['attendance.students'] = function () use ($container) {
            return new StudentsRepository($container['db.connection']);
        };
        $container['attendance.update_attendance'] = function () use ($container) {
            return new UpdateAttendanceList(
                new Stream(),
                $container['attendance.attendances']
            );
        };
        $container['view'] = function () use ($container) {
            $view = new Twig($this->options['twig']['templates'], [
                'cache' => $this->options['twig']['cache'],
                'debug' => $this->options['twig']['debug'],
            ]);
            $view->addExtension(new TwigExtension(
                $container['router'],
                $container['request']->getUri()
            ));

            return $view;
        };
    }
}
