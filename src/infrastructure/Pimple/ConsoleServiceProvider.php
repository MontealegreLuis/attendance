<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Attendance\DoRollCall;
use Codeup\Console\Command\RollCallCommand;
use Codeup\WebDriver\WebDriverAttendanceChecker;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Pimple\Container;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;

class ConsoleServiceProvider extends AttendanceServiceProvider
{
    public function register(Container $container)
    {
        parent::register($container);
        $container['command.roll_call'] = function () use ($container) {
            return new RollCallCommand($container['attendance.do_roll_call']);
        };
        $container['attendance.do_roll_call'] = function () use ($container) {
            $initializer = function (& $wrappedObject, $_, $_, $_, & $initializer) use ($container) {
                $useCase = new DoRollCall(
                    new WebDriverAttendanceChecker(
                        RemoteWebDriver::create(
                            $this->options['webdriver']['host'],
                            $this->options['webdriver']['capabilities'],
                            $this->options['webdriver']['timeout']
                        ),
                        $this->options['dhcp']
                    ),
                    $container['attendance.students'],
                    $container['attendance.attendances']
                );
                $useCase->setPublisher($container['events.publisher']);

                $wrappedObject = $useCase;
                $initializer   = null;

                return true;
            };

            $factory = new LazyLoadingValueHolderFactory();

            // Lazy load the connection to Facebook's Web Driver
            return $factory->createProxy(DoRollCall::class, $initializer);
        };
    }
}
