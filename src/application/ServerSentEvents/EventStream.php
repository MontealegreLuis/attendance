<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\ServerSentEvents;

/**
 * Represent a stream for sending data to a Web page following the
 * Server-Sent-Events specification
 *
 * https://html.spec.whatwg.org/multipage/comms.html#server-sent-events
 */
interface EventStream
{
    public function push($data);
}
