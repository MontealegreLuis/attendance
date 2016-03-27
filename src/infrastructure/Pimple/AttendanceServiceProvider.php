<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Alice\DbalPersister;
use Codeup\Attendance\DoRollCall;
use Codeup\Attendance\UpdateAttendanceList;
use Codeup\Console\Command\RollCallCommand;
use Codeup\Console\Command\SeedDatabaseCommand;
use Codeup\Dbal\AttendancesRepository;
use Codeup\Dbal\BootcampsRepository;
use Codeup\Dbal\EventStoreRepository;
use Codeup\Dbal\MessageTrackerRepository;
use Codeup\Dbal\StudentsRepository;
use Codeup\DomainEvents\EventPublisher;
use Codeup\DomainEvents\PersistEventSubscriber;
use Codeup\JmsSerializer\JsonSerializer;
use Codeup\Messaging\MessagePublisher;
use Codeup\WebDriver\WebDriverAttendanceChecker;
use Doctrine\DBAL\DriverManager;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Igorw\EventSource\Stream;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

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
        $container['db.persister'] = function () use ($container) {
            return new DbalPersister(
                $container['attendance.bootcamps'],
                new StudentsRepository($container['db.connection']),
                $container['attendance.attendances'],
                $container['events.store']
            );
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
        $container['messages.publisher'] = function () use ($container) {
            return new MessagePublisher(
                new MessageTrackerRepository($container['db.connection']),
                $container['events.store']
            );
        };
        $container['attendance.attendances'] = function () use ($container) {
            return new AttendancesRepository($container['db.connection']);
        };
        $container['attendance.bootcamps'] = function () use ($container) {
            return new BootcampsRepository($container['db.connection']);
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
                $container['attendance.attendances']
            );
            $useCase->setPublisher($container['events.publisher']);

            return $useCase;
        };
        $container['attendance.update_attendance'] = function () use ($container) {
            return new UpdateAttendanceList(
                new Stream(),
                $container['attendance.attendances']
            );
        };
        $container['command.roll_call'] = function () use ($container) {
            return new RollCallCommand($container['attendance.do_roll_call']);
        };
        $container['command.db_seeder'] = function () use ($container) {
            return new SeedDatabaseCommand($container['db.persister']);
        };
        $container['view'] = function () use ($container) {
            $view = new Twig($this->options['twig']['templates'], [
                'cache' => $this->options['twig']['cache'],
                'debug' => $this->options['twig']['debug'],
            ]);

            $view->addExtension(new TwigExtension(
                $container['router'],
                $container['request']->getUri()
            ));

            return $view;
        };
    }
}