<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DomainEvents;

interface EventSerializer
{
    /**
     * @param Event $anEvent
     * @return string
     */
    public function serialize(Event $anEvent);
}
