<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Fixtures;

use Nelmio\Alice\Fixtures\Loader;
use Nelmio\Alice\PersisterInterface;

class TwoBootcampsWith2CheckIns
{
    public function load(PersisterInterface $persister, Loader $loader)
    {
        $objects = $loader->load(
            __DIR__ . '/../../var/fixtures/attendance.yml'
        );
        $persister->persist($objects);
    }
}
