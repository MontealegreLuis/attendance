<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup;

use Assert\Assertion;
use Assert\InvalidArgumentException;

class AssertValueIs extends Assertion
{
    public static function macAddress($value, $message = null)
    {
        $message = $message ? $message : "{$value} is not a valid MAC address";
        if (!filter_var($value, FILTER_VALIDATE_MAC)) {
            throw new InvalidArgumentException($message);
        }
    }
}
