<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\DataBuilders;

use Faker\Factory;

trait ProvidesFakeDataGenerator
{
    /** @var \Faker\Generator */
    private $generator;

    /**
     * @return \Faker\Generator
     */
    protected function generator()
    {
        if (!$this->generator) {
            $this->generator = Factory::create();
        }

        return $this->generator;
    }
}
