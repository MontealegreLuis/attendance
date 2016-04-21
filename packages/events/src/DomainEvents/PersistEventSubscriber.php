<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DomainEvents;

class PersistEventSubscriber implements EventSubscriber
{
    /** @var EventStore */
    private $eventStore;

    /**
     * @param EventStore $eventStore
     */
    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @param Event $event
     * @return boolean
     */
    public function isSubscribedTo(Event $event)
    {
        return true;
    }

    /**
     * @param Event $event
     * @return boolean
     */
    public function handle(Event $event)
    {
        $this->eventStore->append($event);
    }
}
