<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

class A
{
    /** @var StudentBuilder */
    private static $student;

    /** @var MacAddressBuilder */
    private static $macAddress;

    /** @var BootcampBuilder */
    private static $bootcamp;

    /** @var StudentHasCheckedInBuilder */
    private static $studentHasCheckedIn;

    /** @var AttendanceBuilder */
    private static $attendance;

    /**
     * @return StudentBuilder
     */
    public static function student()
    {
        if (!self::$student) {
            self::$student = new StudentBuilder();
        }

        return self::$student;
    }

    /**
     * @return MacAddressBuilder
     */
    public static function macAddress()
    {
        if (!self::$macAddress) {
            self::$macAddress = new MacAddressBuilder();
        }

        return self::$macAddress;
    }

    /**
     * @return BootcampBuilder
     */
    public static function bootcamp()
    {
        if (!self::$bootcamp) {
            self::$bootcamp = new BootcampBuilder();
        }

        return self::$bootcamp;
    }

    /**
     * @return StudentHasCheckedInBuilder
     */
    public static function studentHasCheckedIn()
    {
        if (!self::$studentHasCheckedIn) {
            self::$studentHasCheckedIn = new StudentHasCheckedInBuilder();
        }

        return self::$studentHasCheckedIn;
    }

    /**
     * @return AttendanceBuilder
     */
    public static function attendance()
    {
        if (!self::$attendance) {
            self::$attendance = new AttendanceBuilder();
        }

        return self::$attendance;
    }
}
