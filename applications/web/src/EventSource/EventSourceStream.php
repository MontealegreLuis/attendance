<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\EventSource;

use Codeup\ServerSentEvents\EventStream;
use Igorw\EventSource\Stream;

class EventSourceStream implements EventStream
{
    /** @var Stream */
    private $stream;

    /**
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    public function push($data)
    {
        $this
            ->stream
                ->event()
                    ->setData($data)
                ->end()
            ->flush()
        ;
    }
}
