<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

abstract class Identifier
{
    /** @var int */
    private $value;

    /**
     * @param int $value
     */
    private function __construct($value)
    {
        $this->set($value);
    }

    /**
     * @param string $value
     */
    private function set($value)
    {
        AssertValueIs::integer((int) $value, 'Identifiers should be integers');
        AssertValueIs::greaterThan(
            (int) $value,
            0,
            'An identifier value should be greater than 0 ' . get_called_class()
        );
        $this->value = (int) $value;
    }

    /**
     * Identifiers are unsigned integers
     *
     * @param int $value
     * @return Identifier
     */
    public static function fromLiteral($value)
    {
        return new static($value);
    }

    /**
     * @param Identifier $anotherId
     * @return bool
     */
    public function equals(Identifier $anotherId)
    {
        return $this->value === $anotherId->value;
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
