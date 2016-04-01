<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\JmsSerializer;

use Codeup\DomainEvents\Event;
use Codeup\DomainEvents\EventSerializer;

class JsonEventSerializer implements EventSerializer
{
    /** @var JsonSerializer */
    private $serializer;

    /**
     * JsonSerializer constructor.
     */
    public function __construct()
    {
        $this->serializer = new JsonSerializer();
    }

    /**
     * @param Event $anEvent
     * @return string
     */
    public function serialize(Event $anEvent)
    {
        return $this->serializer->serialize($anEvent);
    }
}
