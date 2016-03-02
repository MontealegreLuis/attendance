<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Console\Command;

use Codeup\DomainEvents\EventStore;
use Codeup\Messaging\MessageTracker;
use Codeup\Messaging\PublishedMessage;
use Igorw\EventSource\Stream;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateAttendanceListCommand extends Command
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
        parent::__construct();
        $this->stream = $stream;
        $this->tracker = $tracker;
        $this->store = $store;
    }

    protected function configure()
    {
        $this
            ->setName('codeup:attendance')
            ->setDescription('Send an event whenever a new student is in today\'s class')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (true) {

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

            sleep(5);
        }
    }
}
