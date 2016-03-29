<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\JmsSerializer;

use Codeup\Bootcamps\AttendanceId;
use Codeup\DomainEvents\Event;
use Codeup\DomainEvents\EventSerializer;
use DateTime;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;

class JsonSerializer implements EventSerializer
{
    /** @var \JMS\Serializer\Serializer */
    private $serializer;

    /**
     * JsonSerializer constructor.
     */
    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()
            ->configureHandlers(function (HandlerRegistry $registry) {
                // We only need the value of the ID
                $idSerializer = new IdentifierHandler();
                $registry->registerHandler(
                    'serialization',
                    AttendanceId::class,
                    'json',
                    $idSerializer
                );
                // Use specific format for date/time objects
                $registry->registerHandler(
                    'serialization',
                    DateTime::class,
                    'json',
                    function ($_, DateTime $dateTime, array $_) {
                        return $dateTime->format('Y-m-d H:i:s');
                    }
                );
            })
            ->build();
    }

    /**
     * @param Event $anEvent
     * @return string
     */
    public function serialize(Event $anEvent)
    {
        return $this->serializer->serialize($anEvent, 'json');
    }
}
