<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DomainEvents;

interface EventStore
{
    /**
     * @param StoredEvent $anEvent
     */
    public function append(StoredEvent $anEvent);

    /**
     * @param $lastStoredEventId
     * @return StoredEvent[]
     */
    public function eventsStoredAfter($lastStoredEventId);

    /**
     * @return StoredEvent[]
     */
    public function allEvents();
}