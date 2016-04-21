<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Messaging;

interface MessageTracker
{
    /**
     * @return boolean
     */
    public function hasPublishedMessages();

    /**
     * @return PublishedMessage
     * @throws EmptyExchange
     */
    public function mostRecentMessage();

    /**
     * @param PublishedMessage $mostRecentPublishedMessage
     */
    public function track(PublishedMessage $mostRecentPublishedMessage);

    /**
     * @return int
     */
    public function nextMessageId();
}
