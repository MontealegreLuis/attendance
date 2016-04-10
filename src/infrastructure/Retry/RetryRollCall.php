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
use DateTime;
use Retry\BackOff\ExponentialBackOffPolicy;
use Retry\Policy\SimpleRetryPolicy;
use Retry\RetryProxy;

/**
 * Proxy that retries to execute the `DoRollCall` use case.
 */
class RetryRollCall extends DoRollCall
{
    /** @var RetryProxy */
    private $proxy;

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
        $this->proxy = new RetryProxy(
            new SimpleRetryPolicy($attempts),
            new ExponentialBackOffPolicy($interval)
        );
    }

    public function rollCall(DateTime $today)
    {
        return $this->proxy->call(function (DateTime $today) {
            return parent::rollCall($today);
        }, [$today]);
    }
}
