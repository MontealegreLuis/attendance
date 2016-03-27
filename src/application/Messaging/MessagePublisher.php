<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Messaging;

use Codeup\DomainEvents\EventStore;

class MessagePublisher
{
    /** @var MessageTracker */
    private $tracker;

    /** @var EventStore */
    private $store;

    /**
     * @param MessageTracker $tracker
     * @param EventStore $store
     */
    public function __construct(
        MessageTracker $tracker,
        EventStore $store
    ) {
        $this->tracker = $tracker;
        $this->store = $store;
    }

    public function publishTo(MessageConsumer $consumer)
    {
        if (!$this->tracker->hasPublishedMessages()) {
            $mostRecentMessage = null;
            $events = $this->store->allEvents();
        } else {
            $mostRecentMessage = $this->tracker->mostRecentMessage();
            $events = $this->store->eventsStoredAfter(
                $mostRecentMessage->mostRecentId()
            );
        }

        if (count($events) === 0) {
            return; // No events to publish
        }

        foreach ($events as $event) {
            $consumer->consume($event);
            $lastPublishedEvent = $event;
        }

        if (!$mostRecentMessage) {
            $mostRecentMessage = new PublishedMessage(
                $this->tracker->nextMessageId(),
                $lastPublishedEvent->id()->value()
            );
        } else {
            $mostRecentMessage->updateMostRecentId(
                $lastPublishedEvent->id()->value()
            );
        }

        $this->tracker->track($mostRecentMessage);
    }
}
