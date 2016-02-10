<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Codeup\Bootcamps\MacAddress;
use Faker\Factory;

class MacAddressBuilder
{
    private $factory;
    private $value;

    public function __construct()
    {
        $this->factory = Factory::create();
        $this->reset();
    }

    /**
     * @param string $value
     * @return MacAddressBuilder
     */
    public function withValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return MacAddress
     */
    public function build()
    {
        $address = MacAddress::withValue($this->value);
        $this->reset();

        return $address;
    }

    private function reset()
    {
        $this->value = $this->factory->macAddress;
    }
}
