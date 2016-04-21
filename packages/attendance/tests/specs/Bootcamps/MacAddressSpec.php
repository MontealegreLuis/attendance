<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use Assert\InvalidArgumentException;
use Codeup\Bootcamps\MacAddress;
use PhpSpec\ObjectBehavior;

class MacAddressSpec extends ObjectBehavior
{
    function it_can_be_created_with_a_valid_value()
    {
        $this->beConstructedThrough('withValue', ['e0:ac:cb:82:46:6e']);
        $this->value()->shouldBe('e0:ac:cb:82:46:6e');
    }

    function it_cannot_be_created_with_an_invalid_address()
    {
        $this->beConstructedThrough('withValue', ['this is not an address']);
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_knows_when_it_is_equal_to_another_address()
    {
        $macAddress = 'e0:ac:cb:82:46:6e';
        $this->beConstructedThrough('withValue', [$macAddress]);
        $this->equals(MacAddress::withValue($macAddress))->shouldBe(true);
    }

    function it_can_create_mac_addresses_from_text()
    {
        $this->beConstructedThrough('withValue', ['e0:ac:cb:82:46:6e']);
        $text = ' Codeups-Mini-2 &amp;&amp;10.10.1.4&amp;68:5b:35:ad:d4:79&amp;  26 Minutes,  55 Seconds; Amis-iPad &amp;&amp;10.10.1.5&amp; 54:e4:3a:9c:be:01&amp;  43 Minutes,  32 Seconds;';
        $this::addressesFrom($text)->shouldHaveCount(2);
    }
}
