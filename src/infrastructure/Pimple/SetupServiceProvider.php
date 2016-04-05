<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Alice\DbalPersister;
use Codeup\Console\Command\AttendanceGeneratorCommand;
use Codeup\Console\Command\SeedDatabaseCommand;
use Pimple\Container;

class SetupServiceProvider extends AttendanceServiceProvider
{
    public function register(Container $container)
    {
        parent::register($container);
        $container['command.db_seeder'] = function () use ($container) {
            return new SeedDatabaseCommand($container['db.persister']);
        };
        $container['command.attendance_generator'] = function () use ($container) {
            return new AttendanceGeneratorCommand(
                $container['db.connection'],
                $container['attendance.attendances'],
                $container['events.publisher']
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
