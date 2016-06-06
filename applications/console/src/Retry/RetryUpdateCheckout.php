<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Retry;

use Codeup\Attendance\UpdateStudentsCheckout;
use Codeup\Bootcamps\AttendanceChecker;
use Codeup\Bootcamps\Attendances;
use Codeup\Bootcamps\Students;
use DateTimeInterface;

class RetryUpdateCheckout extends UpdateStudentsCheckout
{
    use RetryProvider;

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
    public function updateStudentsCheckout(DateTimeInterface $today)
    {
        return $this->retry(function (DateTimeInterface $today) {
            return parent::updateStudentsCheckout($today);
        }, [$today]);
    }
}
