<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Pimple;

use Codeup\Dbal\AttendancesRepository;
use Codeup\Dbal\BootcampsRepository;
use Codeup\Dbal\EventStoreRepository;
use Codeup\Dbal\MessageTrackerRepository;
use Codeup\Dbal\StudentsRepository;
use Codeup\DomainEvents\EventPublisher;
use Codeup\DomainEvents\PersistEventSubscriber;
use Codeup\JmsSerializer\JsonEventSerializer;
use Codeup\JmsSerializer\JsonSerializer;
use Doctrine\DBAL\DriverManager;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AttendanceServiceProvider implements ServiceProviderInterface
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

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['events.publisher'] = function () use ($container) {
            $publisher = new EventPublisher();
            $publisher->subscribe(new PersistEventSubscriber(
                $container['events.store']
            ));

            return $publisher;
        };
    }
}
