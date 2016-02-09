<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

class MacAddress
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        $this->setAddress($value);
    }

    /**
     * @param string $value
     * @return MacAddress
     */
    public static function withValue($value)
    {
        return new MacAddress($value);
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @throws \Assert\InvalidArgumentException
     */
    private function setAddress($value)
    {
        AssertValueIs::macAddress($value);
        $this->value = strtolower($value);
    }

    /**
     * @param string $address
     * @return bool
     */
    public static function isValid($address)
    {
        return preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', trim($address)) === 1;
    }

    /**
     * @param MacAddress $anAddress
     * @return bool
     */
    public function equals(MacAddress $anAddress)
    {
        return $this->value === $anAddress->value;
    }
}
