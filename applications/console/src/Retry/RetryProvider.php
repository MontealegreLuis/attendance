<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Retry;

use Retry\BackOff\ExponentialBackOffPolicy;
use Retry\RetryProxy;
use Exception;

trait RetryProvider
{
    /** @var RetryProxy */
    private $proxy;

    /** @var RecordingRetryPolicy */
    private $retryPolicy;

    /**
     * @param int $attempts
     * @param int $interval In microseconds
     */
    protected function configureRetries($attempts, $interval)
    {
        $this->retryPolicy = new RecordingRetryPolicy($attempts);
        $this->proxy = new RetryProxy(
            $this->retryPolicy,
            new ExponentialBackOffPolicy($interval)
        );
    }

    /**
     * @param callable $action
     * @param array $arguments
     * @return mixed
     */
    protected function retry(callable $action, array $arguments)
    {
        try {
            return $this->proxy->call($action, $arguments);
        } catch (Exception $exception) {
            throw RetriesExhausted::using($this->retryPolicy);
        }
    }
}
