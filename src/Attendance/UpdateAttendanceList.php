<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Attendance;

use Codeup\DomainEvents\StoredEvent;
use Codeup\Messaging\MessageConsumer;
use Igorw\EventSource\Stream;

class UpdateAttendanceList implements MessageConsumer
{
    /** @var Stream */
    private $stream;

    /**
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * @param StoredEvent $event
     */
    public function consume(StoredEvent $event)
    {
        $this
            ->stream
               ->event()
                    ->setData($event->body())
                ->end()
            ->flush()
        ;
    }
}
