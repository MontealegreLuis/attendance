<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Attendance\DoRollCall;
use Codeup\Attendance\UpdateStudentsCheckout;
use Codeup\Console\Command\Listeners\PhantomJsListener;
use Codeup\Console\Command\Listeners\PhpServerListener;
use Codeup\Console\Command\RollCallCommand;
use Codeup\Console\Command\UpdateAttendanceCommand;
use Codeup\Retry\RetryRollCall;
use Codeup\Retry\RetryUpdateCheckout;
use Codeup\TestHelpers\HeadlessRunner;
use Codeup\WebDriver\WebDriverAttendanceChecker;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Pimple\Container;
use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ConsoleServiceProvider extends DatabaseServiceProvider
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        parent::register($container);
        $container['command.roll_call'] = function () use ($container) {
            return new RollCallCommand($container['attendance.do_roll_call']);
        };
        $container['command.update_checkout'] = function () use ($container) {
            return new UpdateAttendanceCommand($container['attendance.update_checkout']);
        };
        $container['attendance.update_checkout'] = function () use ($container) {
            $initializer = function (& $wrappedObject, $_, $__, $___, & $initializer) use ($container) {
                $useCase = new RetryUpdateCheckout(
                    $container['attendance.checker'],
                    $container['attendance.students'],
                    $container['attendance.attendances'],
                    $this->options['retry']['attempts'],
                    $this->options['retry']['interval']
                );

                $wrappedObject = $useCase;
                $initializer   = null;

                return true;
            };

            $factory = new LazyLoadingValueHolderFactory();

            // Lazy load the connection to Facebook's Web Driver
            return $factory->createProxy(UpdateStudentsCheckout::class, $initializer);
        };
        $container['attendance.do_roll_call'] = function () use ($container) {
            $initializer = function (& $wrappedObject, $_, $__, $___, & $initializer) use ($container) {
                $useCase = new RetryRollCall(
                    $container['attendance.checker'],
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
        $container['attendance.checker'] = function () {
            return new WebDriverAttendanceChecker(
                RemoteWebDriver::create(
                    $this->options['webdriver']['host'],
                    $this->options['webdriver']['capabilities'],
                    $this->options['webdriver']['timeout']
                ),
                $this->options['dhcp']
            );
        };
        $container['console.runner'] = function () {
            return new HeadlessRunner($this->options['webdriver']['host']);
        };
        $container['console.listeners.phantomjs'] = function () use ($container) {
            return new PhantomJsListener($container['console.runner']);
        };
        $container['console.listeners.php_server'] = function () use ($container) {
            return new PhpServerListener($container['console.runner']);
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
