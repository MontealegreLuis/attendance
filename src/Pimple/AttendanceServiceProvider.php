<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Attendance\DoRollCall;
use Codeup\Console\Command\RollCallCommand;
use Codeup\Console\Command\UpdateAttendanceListCommand;
use Codeup\Dbal\AttendancesRepository;
use Codeup\Dbal\EventStoreRepository;
use Codeup\Dbal\MessageTrackerRepository;
use Codeup\Dbal\StudentsRepository;
use Codeup\DomainEvents\EventPublisher;
use Codeup\DomainEvents\PersistEventSubscriber;
use Codeup\JmsSerializer\JsonSerializer;
use Codeup\WebDriver\WebDriverAttendanceChecker;
use Doctrine\DBAL\DriverManager;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Igorw\EventSource\Stream;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AttendanceServiceProvider implements ServiceProviderInterface
{
    /** @var array */
    private $options;

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
        $container['events.publisher'] = function () use ($container) {
            $publisher = new EventPublisher();
            $publisher->subscribe(new PersistEventSubscriber(
                $container['events.store']
            ));

            return $publisher;
        };
        $container['attendance.do_roll_call'] = function () use ($container) {
            $useCase = new DoRollCall(
                new WebDriverAttendanceChecker(
                    RemoteWebDriver::create(
                        $this->options['webdriver']['host'],
                        $this->options['webdriver']['capabilities'],
                        $this->options['webdriver']['timeout']
                    ),
                    $this->options['dhcp']
                ),
                new StudentsRepository($container['db.connection']),
                new AttendancesRepository($container['db.connection'])
            );
            $useCase->setPublisher($container['events.publisher']);

            return $useCase;
        };
        $container['command.roll_call'] = function () use ($container) {
            return new RollCallCommand($container['attendance.do_roll_call']);
        };
        $container['command.update_attendance'] = function () use ($container) {
            return new UpdateAttendanceListCommand(
                new Stream(),
                new MessageTrackerRepository($container['db.connection']),
                $container['events.store']
            );
        };
    }
}
