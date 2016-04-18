<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Fixtures;

use Faker\Provider\Base as Provider;
use Nelmio\Alice\Fixtures\Loader;
use Nelmio\Alice\PersisterInterface;

class Bootcamp5Students1OnTime1Late
{
    public function load(PersisterInterface $persister, Provider $provider)
    {
        $objects = (new Loader('en_US', [$provider]))->load(
            __DIR__ . '/../../../var/fixtures/bootcamp-2-attending-5-total.yml'
        );
        $persister->persist($objects);
    }
}
