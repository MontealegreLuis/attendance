<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Codeup\Bootcamps\StudentId;

class StudentIdSpec extends IdentifierSpec
{
    function anotherIdentifier($value)
    {
        return StudentId::fromLiteral($value);
    }
}
