<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup;

use PhpSpec\ObjectBehavior;

class MacAddressSpec extends ObjectBehavior
{
    function it_should_be_created_with_a_valid_value()
    {
        $this->beConstructedThrough('withValue', ['00-80-C8-E3-4C-BD']);
        $this->value()->shouldBe('00-80-C8-E3-4C-BD');
    }

    function it_should_not_be_created_with_an_invalid_address()
    {
        $this->beConstructedThrough('withValue', ['this is not an address']);
        $this->shouldThrow(\Exception::class)->duringInstantiation();
    }
}
