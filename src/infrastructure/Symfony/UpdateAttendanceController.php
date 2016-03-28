<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Symfony;

use Codeup\Attendance\UpdateAttendanceList;
use Codeup\Messaging\MessagePublisher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UpdateAttendanceController
{
    /** @var MessagePublisher */
    private $publisher;

    /** @var UpdateAttendanceList */
    private $consumer;

    /**
     * @param MessagePublisher $publisher
     * @param UpdateAttendanceList $consumer
     */
    public function __construct(
        MessagePublisher $publisher,
        UpdateAttendanceList $consumer
    ) {
        $this->publisher = $publisher;
        $this->consumer = $consumer;
    }

    public function updateListAction()
    {
        $response = new StreamedResponse(function () {
            $this->publisher->publishTo($this->consumer);
            sleep(3);
        });

        $response->prepare(Request::createFromGlobals());
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->send();
    }
}
