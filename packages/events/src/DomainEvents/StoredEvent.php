<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DomainEvents;

use DateTime;

/**
 * Representation of a persisted domain event
 */
class StoredEvent implements Event
{
    /** @var StoredEventId */
    private $id;

    /** @var string */
    private $body;

    /** @var string */
    private $type;

    /** @var DateTime */
    private $occurredOn;

    /**
     * @param StoredEventId $storedEventId
     * @param string $body
     * @param string $type
     * @param DateTime $occurredOn
     */
    public function __construct(
        StoredEventId $storedEventId,
        $body,
        $type,
        DateTime $occurredOn
    ) {
        $this->body = $body;
        $this->type = $type;
        $this->occurredOn = $occurredOn;
        $this->id = $storedEventId;
    }

    /**
     * @param array $values
     * @return StoredEvent
     */
    public static function from(array $values)
    {
        return new StoredEvent(
            StoredEventId::fromLiteral($values['event_id']),
            $values['body'],
            $values['type'],
            DateTime::createFromFormat('Y-m-d H:i:s', $values['occurred_on'])
        );
    }

    /**
     * @return StoredEventId
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
