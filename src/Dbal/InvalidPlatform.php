<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use RuntimeException;

class InvalidPlatform extends RuntimeException
{
    public static function named($platForm)
    {
        return new InvalidPlatform(
            "Invalid platform name {$platForm} expecting either mysql or sqlite"
        );
    }
}
