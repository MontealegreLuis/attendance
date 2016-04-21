<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Retry;

use DateTime;
use Exception;
use Retry\Policy\SimpleRetryPolicy;
use Retry\RetryContextInterface;

class RecordingRetryPolicy extends SimpleRetryPolicy
{
    /** @var array */
    private $recordedAttempts;

    /**
     * @param RetryContextInterface $context
     * @param Exception $exception
     */
    public function registerException(
        RetryContextInterface $context,
        Exception $exception
    ) {
        parent::registerException($context, $exception);
        $this->recordFailedAttempt($exception);
    }

    /**
     * @param Exception $exception
     */
    protected function recordFailedAttempt(Exception $exception)
    {
        $this->recordedAttempts[] = sprintf(
            '#%d --- Failed attempt at %s with message %s',
            count($this->recordedAttempts) + 1,
            (new DateTime('now'))->format('Y-m-d H:i:s'),
            $exception->getMessage()
        );
    }

    /**
     * @return string
     */
    public function attempts()
    {
        return PHP_EOL . implode(PHP_EOL . PHP_EOL, $this->recordedAttempts);
    }
}
