<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Bootcamps;

interface Bootcamps
{
    /**
     * @param Bootcamp $bootcamp
     */
    public function add(Bootcamp $bootcamp);

    /**
     * @param BootcampId $bootcampId
     * @return Bootcamp
     */
    public function with(BootcampId $bootcampId);
}
