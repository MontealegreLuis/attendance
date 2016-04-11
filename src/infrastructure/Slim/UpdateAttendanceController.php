<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Slim;

use Codeup\Attendance\UpdateAttendanceList;
use Codeup\Messaging\MessagePublisher;
use Slim\Http\Response;

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

    public function updateAttendanceList($_, Response $response)
    {
        ob_start();
        $this->publisher->publishTo($this->consumer);
        $body = ob_get_clean();

        return $response
            ->withAddedHeader('Content-Type', 'text/event-stream')
            ->write($body)
        ;
    }
}
