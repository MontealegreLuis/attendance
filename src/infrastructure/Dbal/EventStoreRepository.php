<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\DomainEvents\Event;
use Codeup\DomainEvents\EventSerializer;
use Codeup\DomainEvents\EventStore;
use Codeup\DomainEvents\StoredEvent;
use Codeup\DomainEvents\StoredEventId;
use Doctrine\DBAL\Connection;

class EventStoreRepository implements EventStore
{
    use ProvidesIdentifiers;

    /** @var Connection */
    private $connection;

    /** @var EventSerializer */
    private $serializer;

    /**
     * @param Connection $connection
     * @param EventSerializer $serializer
     */
    public function __construct(
        Connection $connection,
        EventSerializer $serializer
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param Event $anEvent
     * @return StoredEvent
     */
    public function append(Event $anEvent)
    {
        $storedEvent = $this->from($anEvent);
        $this->connection->insert('events', [
            'event_id' => $storedEvent->id(),
            'body' => $storedEvent->body(),
            'type' => $storedEvent->type(),
            'occurred_on' => $storedEvent->occurredOn()->format('Y-m-d H:i:s'),
        ]);

        return $storedEvent;
    }

    /**
     * @param Event $event
     * @return StoredEvent
     */
    public function from(Event $event)
    {
        return new StoredEvent(
            $this->nextEventId(),
            $this->serializer->serialize($event),
            get_class($event),
            $event->occurredOn()
        );
    }

    /**
     * @param integer $lastStoredEventId
     * @return StoredEvent[]
     */
    public function eventsStoredAfter($lastStoredEventId)
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from('events', 'e')
            ->where('e.event_id > :eventId')
            ->setParameter('eventId', $lastStoredEventId)
            ->orderBy('e.event_id')
        ;

        return array_map(function (array $values) {
            return StoredEvent::from($values);
        }, $builder->execute()->fetchAll());
    }

    /**
     * @return StoredEvent[]
     */
    public function allEvents()
    {
        $builder = $this->connection->createQueryBuilder();
        $builder
            ->select('*')
            ->from('events', 'e')
            ->orderBy('e.event_id')
        ;
        return array_map(function (array $values) {
            return StoredEvent::from($values);
        }, $builder->execute()->fetchAll());
    }

    /**
     * @return StoredEventId
     */
    public function nextEventId()
    {
        return StoredEventId::fromLiteral(
            $this->nextIdentifierValue($this->connection, 'events_seq')
        );
    }
}
