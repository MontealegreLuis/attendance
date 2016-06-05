<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Retry;

use Codeup\Attendance\DoRollCall;
use Codeup\Bootcamps\AttendanceChecker;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\Students;
use DateTimeInterface;
use Exception;
use Retry\BackOff\ExponentialBackOffPolicy;
use Retry\RetryProxy;

/**
 * Proxy that retries to execute the `DoRollCall` use case.
 */
class RetryRollCall extends DoRollCall
{
    use RetryProvider;

    /**
     * @param AttendanceChecker $checker
     * @param Students $students
     * @param Attendances $attendances
     * @param $attempts Max attempts
     * @param $interval In microseconds
     */
    public function __construct(
        AttendanceChecker $checker,
        Students $students,
        Attendances $attendances,
        $attempts,
        $interval
    ) {
        parent::__construct($checker, $students, $attendances);
        $this->configureRetries($attempts, $interval);
    }

    /**
     * @param DateTimeInterface $today
     * @return \Codeup\Bootcamps\Student[]
     * @throws RetriesExhausted
     */
    public function rollCall(DateTimeInterface $today)
    {
        $this->retry(function (DateTimeInterface $today) {
            return parent::rollCall($today);
        }, [$today]);
    }
}
