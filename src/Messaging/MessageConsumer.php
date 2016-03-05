<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Messaging;

use Codeup\DomainEvents\StoredEvent;

interface MessageConsumer
{
    /**
     * @param StoredEvent $event
     */
    public function consume(StoredEvent $event);
}
