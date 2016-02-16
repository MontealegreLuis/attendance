<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Codeup\Bootcamps;

use PhpSpec\ObjectBehavior;
use Assert\InvalidArgumentException;

abstract class IdentifierSpec extends ObjectBehavior
{
    function it_should_be_created_from_an_integer_value()
    {
        $this->beConstructedThrough('fromLiteral', [1]);
        $this->value()->shouldBe(1);
    }

    function it_should_not_be_created_from_a_non_integer_value()
    {
        $this->beConstructedThrough('fromLiteral', ['invalid']);
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_should_know_when_it_is_equal_to_another_id()
    {
        $this->beConstructedThrough('fromLiteral', [2]);
        $this->equals($this->anotherIdentifier(2))->shouldBe(true);
    }

    function it_should_be_converted_to_string()
    {
        $this->beConstructedThrough('fromLiteral', [23]);
        $this->__toString()->shouldBe('23');
    }

    /**
     * @param int $value
     * @return \Codeup\Bootcamps\Identifier
     */
    abstract function anotherIdentifier($value);
}
