<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Twig\Extensions;

use Twig_Extension as Extension;
use Twig_SimpleFunction as SimpleFunction;

class AttendanceExtension extends Extension
{
    public function getFunctions()
    {
        return [
            new SimpleFunction('percentage', function ($currentCount, $total) {
                return $currentCount * 100 / $total;
            }),
        ];
    }

    public function getName()
    {
        return 'attendance_extension';
    }
}
