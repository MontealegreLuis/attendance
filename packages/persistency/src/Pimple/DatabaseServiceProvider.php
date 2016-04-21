<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Alice\AttendanceProvider;
use Codeup\Alice\DbalPersister;
use Codeup\Console\Command\SeedDatabaseCommand;
use Codeup\Dbal\AttendancesRepository;
use Codeup\Dbal\BootcampsRepository;
use Codeup\Dbal\EventStoreRepository;
use Codeup\Dbal\MessageTrackerRepository;
use Codeup\Dbal\StudentsRepository;
use Codeup\JmsSerializer\JsonEventSerializer;
use Codeup\JmsSerializer\JsonSerializer;
use Doctrine\DBAL\DriverManager;
use Nelmio\Alice\Fixtures\Loader;
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
        $container['fixtures.loader'] = function () use ($container) {
            return new Loader('en_US', [new AttendanceProvider(
                $container['events.store'],
                $container['messages.tracker'],
                $container['attendance.attendances'],
                $container['attendance.bootcamps'],
                $container['attendance.students']
            )]);
        };
        $container['command.db_seeder'] = function () use ($container) {
            return new SeedDatabaseCommand(
                $container['db.persister'],
                $container['fixtures.loader']
            );
        };
        $container['db.persister'] = function () use ($container) {
            return new DbalPersister(
                $container['attendance.bootcamps'],
                $container['attendance.students'],
                $container['attendance.attendances'],
                $container['events.store']
            );
        };
    }
}
