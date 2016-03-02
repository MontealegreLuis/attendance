<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console;

use Pimple\Container;
use Symfony\Component\Console\Application;

class AttendanceApplication extends Application
{
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct('Codeup attendance application', '1.0.0');
        $this->addCommands([
            $container['command.roll_call'],
            $container['command.update_attendance'],
        ]);
    }
}
