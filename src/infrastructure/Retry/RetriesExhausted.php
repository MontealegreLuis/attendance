<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Retry;

use RuntimeException;

class RetriesExhausted extends RuntimeException
{
    /**
     * @param RecordingRetryPolicy $policy
     * @return RetriesExhausted
     */
    public static function using(RecordingRetryPolicy $policy)
    {
        return new self($policy->attempts());
    }
}
