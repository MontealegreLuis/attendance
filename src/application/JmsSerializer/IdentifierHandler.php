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
     * @param array $_ Type
     * @return int
     */
    public function serialize($_, Identifier $id, array $_)
    {
        return $id->value();
    }

    /**
     * @param $_ Visitor
     * @param Identifier $id
     * @param array $_ Type
     * @return int
     */
    public function __invoke($_, Identifier $id, array $_)
    {
        return $this->serialize($_, $id, $_);
    }
}
