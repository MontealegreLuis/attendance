<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\DomainEvents\EventStore;
use Codeup\Messaging\MessageTracker;
use Codeup\Messaging\PublishedMessage;
use Igorw\EventSource\Stream;

class UpdateAttendanceList
{
    /** @var Stream */
    private $stream;

    /** @var MessageTracker */
    private $tracker;

    /** @var EventStore */
    private $store;

    /**
     * @param Stream $stream
     * @param MessageTracker $tracker
     * @param EventStore $store
     */
    public function __construct(
        Stream $stream,
        MessageTracker $tracker,
        EventStore $store
    ) {
        $this->stream = $stream;
        $this->tracker = $tracker;
        $this->store = $store;
    }

    public function updateList()
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

        foreach ($events as $event) {
            $this
                ->stream
                   ->event()
                        ->setData($event->body())
                    ->end()
                ->flush()
            ;
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
