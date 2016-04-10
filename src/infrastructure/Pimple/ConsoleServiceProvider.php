<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Attendance\DoRollCall;
use Codeup\Console\Command\Listeners\PhantomJsListener;
use Codeup\Console\Command\Listeners\PhpServerListener;
use Codeup\Console\Command\RollCallCommand;
use Codeup\Retry\RetryRollCall;
use Codeup\WebDriver\WebDriverAttendanceChecker;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Pimple\Container;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
                $useCase = new RetryRollCall(
                    new WebDriverAttendanceChecker(
                        RemoteWebDriver::create(
                            $this->options['webdriver']['host'],
                            $this->options['webdriver']['capabilities'],
                            $this->options['webdriver']['timeout']
                        ),
                        $this->options['dhcp']
                    ),
                    $container['attendance.students'],
                    $container['attendance.attendances'],
                    $this->options['retry']['attempts'],
                    $this->options['retry']['interval']
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
        $container['console.listeners.phantomjs'] = function () {
            return new PhantomJsListener();
        };
        $container['console.listeners.php_server'] = function () {
            return new PhpServerListener();
        };
        $container['console.dispatcher'] = function () use ($container) {
            $dispatcher = new EventDispatcher();
            $dispatcher->addListener(ConsoleEvents::COMMAND, [
                $container['console.listeners.phantomjs'],
                'startPhantomJs'
            ]);
            $dispatcher->addListener(ConsoleEvents::TERMINATE, [
                $container['console.listeners.phantomjs'],
                'stopPhantomJs'
            ]);
            $dispatcher->addListener(ConsoleEvents::COMMAND, [
                $container['console.listeners.php_server'],
                'startPhpServer'
            ]);
            $dispatcher->addListener(ConsoleEvents::TERMINATE, [
                $container['console.listeners.php_server'],
                'stopPhpServer'
            ]);


            return $dispatcher;
        };
    }
}
