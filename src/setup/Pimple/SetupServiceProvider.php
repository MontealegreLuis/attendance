<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Alice\AttendanceProvider;
use Codeup\Alice\DbalPersister;
use Codeup\Attendance\AttendanceAnalyzer;
use Codeup\Attendance\AttendanceGenerator;
use Codeup\Console\Command\AttendanceGeneratorCommand;
use Codeup\Console\Command\SeedDatabaseCommand;
use Nelmio\Alice\Fixtures\Loader;
use Pimple\Container;

class SetupServiceProvider extends AttendanceServiceProvider
{
    public function register(Container $container)
    {
        parent::register($container);
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
        $container['command.attendance_generator'] = function () use ($container) {
            return new AttendanceGeneratorCommand(
                new AttendanceAnalyzer($container['db.connection']),
                new AttendanceGenerator(
                    $container['attendance.attendances'],
                    $container['events.publisher']
                )
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
