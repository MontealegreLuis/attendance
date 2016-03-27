<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Codeup\Messaging\MessageTracker;
use Codeup\Messaging\PublishedMessage;
use Doctrine\DBAL\Connection;

class MessageTrackerRepository implements MessageTracker
{
    use ProvidesIdentifiers;

    /** @var Connection */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return bool
     */
    public function hasPublishedMessages()
    {
        $builder = $this->connection->createQueryBuilder();

        $builder
            ->select('COUNT(*)')
            ->from('published_messages', 'm')
        ;

        return $builder->execute()->fetchColumn(0) > 0;
    }

    /**
     * @return PublishedMessage
     */
    public function mostRecentMessage()
    {
        $builder = $this->connection->createQueryBuilder();

        $builder
            ->select('*')
            ->from('published_messages', 'm')
            ->getMaxResults(1)
        ;
        $message = $builder->execute()->fetch();

        return new PublishedMessage(
            $message['message_id'],
            $message['most_recent_message_id']
        );
    }

    /**
     * @param PublishedMessage $mostRecentPublishedMessage
     */
    public function track(PublishedMessage $mostRecentPublishedMessage)
    {
        if ($this->hasPublishedMessages()) {
            $this->update($mostRecentPublishedMessage);
        } else {
            $this->insert($mostRecentPublishedMessage);
        }
    }

    /**
     * @return int
     */
    public function nextMessageId()
    {
        return $this->nextIdentifierValue($this->connection, 'messages_seq');
    }

    /**
     * @param PublishedMessage $mostRecentPublishedMessage
     */
    private function update(PublishedMessage $mostRecentPublishedMessage)
    {
        $this->connection->update('published_messages', [
            'most_recent_message_id' => $mostRecentPublishedMessage->mostRecentId(),
        ], [
            'message_id' => $mostRecentPublishedMessage->id(),
        ]);
    }

    /**
     * @param PublishedMessage $mostRecentPublishedMessage
     */
    private function insert(PublishedMessage $mostRecentPublishedMessage)
    {
        $this->connection->insert('published_messages', [
            'message_id' => $this->nextIdentifierValue($this->connection, 'messages_seq'),
            'most_recent_message_id' => $mostRecentPublishedMessage->mostRecentId(),
        ]);
    }
}
