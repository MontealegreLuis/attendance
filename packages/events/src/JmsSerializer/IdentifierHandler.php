<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\JmsSerializer;

use Codeup\Bootcamps\Identifier;

class IdentifierHandler
{
    /**
     * @param $_ Visitor
     * @param Identifier $id
     * @param array $__ Type
     * @return int
     */
    public function serialize($_, Identifier $id)
    {
        return $id->value();
    }

    /**
     * @param $_ Visitor
     * @param Identifier $id
     * @param array $__ Type
     * @return int
     */
    public function __invoke($_, Identifier $id)
    {
        return $this->serialize($_, $id);
    }
}
