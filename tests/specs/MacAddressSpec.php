<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup;

use Codeup\MacAddress;
use PhpSpec\ObjectBehavior;

class MacAddressSpec extends ObjectBehavior
{
    function it_should_be_created_with_a_valid_value()
    {
        $this->beConstructedThrough('withValue', ['e0:ac:cb:82:46:6e']);
        $this->value()->shouldBe('e0:ac:cb:82:46:6e');
    }

    function it_should_not_be_created_with_an_invalid_address()
    {
        $this->beConstructedThrough('withValue', ['this is not an address']);
        $this->shouldThrow(\Exception::class)->duringInstantiation();
    }

    function it_should_know_when_it_is_equal_to_another_address()
    {
        $this->beConstructedThrough('withValue', ['e0:ac:cb:82:46:6e']);
        $this->equals(MacAddress::withValue('e0:ac:cb:82:46:6e'))->shouldBe(true);
    }
}
