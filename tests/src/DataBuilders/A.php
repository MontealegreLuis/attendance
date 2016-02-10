<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

class A
{
    /**
     * @return StudentBuilder
     */
    public static function student()
    {
        return new StudentBuilder();
    }

    /**
     * @return MacAddressBuilder
     */
    public static function macAddress()
    {
        return new MacAddressBuilder();
    }
}
