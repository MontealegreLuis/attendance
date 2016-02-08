<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup;

class MacAddress
{
    /** @var string */
    private $value;

    private function __construct($value)
    {
        $this->setAddress($value);
    }

    public static function withValue($value)
    {
        return new MacAddress($value);
    }

    public function value()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @throws \Exception
     */
    private function setAddress($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_MAC)) {
            throw new \Exception("{$value} is not a valid MAC address");
        }
        $this->value = $value;
    }
}
