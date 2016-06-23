<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Dbal\AttendancesRepository;
use Codeup\Dbal\BootcampsRepository;
use Codeup\Dbal\EventStoreRepository;
use Codeup\Dbal\MessageTrackerRepository;
use Codeup\Dbal\StudentsRepository;
use Codeup\DomainEvents\EventPublisher;
use Codeup\DomainEvents\PersistEventSubscriber;
use Codeup\JmsSerializer\JsonEventSerializer;
use Codeup\JmsSerializer\JsonSerializer;
use Doctrine\DBAL\DriverManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DatabaseServiceProvider implements ServiceProviderInterface
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

    public function register(Container $container)
    {
        $container['db.connection'] = function () {
            return DriverManager::getConnection($this->options['dbal']);
        };
        $container['events.store'] = function () use ($container) {
            return new EventStoreRepository(
                $container['db.connection'],
                new JsonEventSerializer(new JsonSerializer())
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
        $container['messages.tracker'] = function () use ($container) {
            return new MessageTrackerRepository($container['db.connection']);
        };
        $container['events.publisher'] = function () use ($container) {
            $publisher = new EventPublisher();
            $publisher->subscribe(new PersistEventSubscriber(
                $container['events.store']
            ));

            return $publisher;
        };
    }
}
