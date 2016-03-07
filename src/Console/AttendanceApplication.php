<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console;

use Codeup\Console\Command\DropDatabaseCommand;
use Codeup\Console\Command\CreateDatabaseCommand;
use Codeup\Console\Command\ResetDatabaseCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Pimple\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;

class AttendanceApplication extends Application
{
    /**
     * @param Container $container
     * @param HelperSet $helperSet
     */
    public function __construct(Container $container, HelperSet $helperSet)
    {
        parent::__construct('Codeup attendance application', '1.0.0');
        $this->setHelperSet($helperSet);
        $this->addCommands([
            $container['command.roll_call'],
            $container['command.db_seeder'],
            new CreateDatabaseCommand(),
            new DropDatabaseCommand(),
            new MigrateCommand(),
            new ResetDatabaseCommand(),
        ]);
    }
}
