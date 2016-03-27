<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Messaging;

class PublishedMessage
{
    /** @var integer */
    private $id;

    /** @var integer */
    private $mostRecentId;

    /**
     * @param integer $id
     * @param integer $mostRecentMessageId
     */
    public function __construct($id, $mostRecentMessageId)
    {
        $this->mostRecentId = $mostRecentMessageId;
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function mostRecentId()
    {
        return $this->mostRecentId;
    }

    /**
     * @param integer $mostRecentId
     */
    public function updateMostRecentId($mostRecentId)
    {
        $this->mostRecentId = $mostRecentId;
    }

    /**
     * 2 messages are equal if they have the same ID
     *
     * @param PublishedMessage $message
     * @return boolean
     */
    public function equals(PublishedMessage $message)
    {
        return $this->id == $message->id;
    }
}
